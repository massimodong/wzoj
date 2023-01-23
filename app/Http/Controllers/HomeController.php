<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Cache;
use Lang;
use Gate;
use Auth;
use DB;

class HomeController extends Controller
{
  const USER_LIMIT = 100;
  const SEARCH_PAGE_LIMIT = 20;
  public function index(Request $request){
    if(!empty(\Request::get('contests'))){
      return redirect('/contests');
    }

    $diyPage = NULL;
    if(strlen(ojoption('home_diy'))){
      $url = ojoption('home_diy');
      $diyPage = Cache::tags(['diyPages'])->rememberForever($url, function() use($url){
        return \App\DiyPage::where('url', $url)->first();
      });
    }

    //recent contests
    $recent_contests = NULL;
    if(Auth::check()){
      $recent_contests = collect();
      foreach(Auth::user()->problemsets() as $problemset){
        if($problemset->type != 'set'){
          $recent_contests->push($problemset);
        }
      }
      $recent_contests = $recent_contests->sortByDesc('contest_start_at')->take(4);
    }else{
      $recent_contests = Cache::tags(['wzoj'])->remember('recent_contests', CACHE_ONE_MINUTE, function(){
          return \App\Problemset::where('type','<>', 'set')->where('public', 1)->orderBy('contest_start_at', 'desc')->take(4)->get();
      });
    }

    $groups = [];
    if(Auth::check()){
      $user = $request->user();
      $groups = Cache::tags(['user_groups'])->rememberForever($user->id, function() use($user){
        return $user->groups;
      });
    }

    //homeworks
    $homework_flag = false;
    $group_homeworks = array();
    if(Auth::check()){
      foreach($groups as $group){
        $problemset_ids = [];
        $problem_ids = [];
        $total_score = 0;
        $user_score = 0;

        $homeworks = Cache::tags(['group_homeworks'])->rememberForever($group->id, function() use($group){
          return $group->homeworks;
        });
        foreach($homeworks as $problem){
          array_push($problemset_ids, $problem->pivot->problemset_id);
          array_push($problem_ids, $problem->id);
          $homework_flag = true;
          $total_score += 100;
        }

        $max_scores = max_scores([Auth::user()->id], $problemset_ids, $problem_ids);
        foreach($homeworks as $problem){
          $sc = $max_scores[Auth::user()->id][$problem->pivot->problemset_id][$problem->id];
          if($sc>0) $user_score+=$sc;
        }

        if($total_score){
          array_push($group_homeworks, [
              'group' => $group,
              'total_score' => $total_score,
              'user_score' => $user_score,
          ]);
        }
      }
    }

    if(Auth::check()){
      $view_history = Cache::tags('wzoj')->get('view_history.'.$request->user()->id, collect());
      $problemset_ids = $view_history->map(function($item, $key){
        return $item["psid"];
      });
      $problem_ids = $view_history->map(function($item, $key){
        return $item["pid"];
      });
      $view_history_max_scores = max_scores([Auth::user()->id], $problemset_ids, $problem_ids);
    }else{
      $view_history = collect();
      $view_history_max_scores = [];
    }

    $sidePanels = Cache::tags(['wzoj'])->rememberForever('sidepanels', function(){
      return \App\SidePanel::where('index', '>', 0)->orderBy('index', 'asc')->get();
    });

    return view('home',[
      'home_diy' => $diyPage,
      'recent_contests' => $recent_contests,
      'group_homeworks' => $homework_flag?$group_homeworks:NULL,
      'groups' => $groups,
      'view_history' => $view_history,
      'view_history_max_scores' => $view_history_max_scores,
      'sidePanels' => $sidePanels,
    ]);
  }

  public function ranklist(Request $request){
    $users = User::orderBy('cnt_ac', 'desc')
        ->withoutAdmin()
        ->paginate(100);
    return view('ranklist', [
        'users' => $users,
        'start_rank' => $users->firstItem(),
    ]);
  }

  public function getSorry(Request $request){
    if((!Auth::check()) || $request->user()->bot_tendency < 100){
      return redirect('/');
    }
    \Session::put('url.intended', \URL::previous());
    return view('sorry');
  }

  public function postSorry(Request $request){
    if(!(Auth::check())) return redirect('/');
    $this->validate($request,[
      'captcha' => 'required|captcha']);
    \App\User::where('id', $request->user()->id)
      ->update(['bot_tendency' => 0]);
    return redirect()->intended('/');
  }

  private function searchProblem($request){
    $user = Auth::user();
    $psids = $user->problemsets()->map(function($item, $key){
      return $item->id;
    })->toArray();

    if(isset($request->tags)){
      $subquery = \App\Problem::join('problem_problem_tag', 'problems.id', '=', 'problem_problem_tag.problem_id')
                         ->join('problem_problemset', 'problems.id', '=', 'problem_problemset.problem_id')
                         ->whereIn('problem_problemset.problemset_id', $psids)
                         ->whereIn('problem_problem_tag.problem_tag_id', $request->tags)
                         ->where('problems.name', 'like', '%'.$request->name.'%')
                         ->select('problems.id', 'problem_problem_tag.problem_tag_id', 'problem_problemset.problemset_id')
                         ->distinct();
      $res = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
               ->mergeBindings($subquery->toBase())
               ->select(DB::raw('id, problemset_id, COUNT(*) as count'))
               ->groupBy("id", 'problemset_id')
               ->orderBy("count", "desc")
               ->orderBy('id', 'asc')
               ->orderBy('problemset_id', 'asc')
               ->paginate(self::SEARCH_PAGE_LIMIT)
               ->withQueryString();
    }else{
      if($request->name == "") abort(400);
      $res = \App\Problem::join('problem_problemset', 'problems.id', '=', 'problem_problemset.problem_id')
                         ->whereIn('problem_problemset.problemset_id', $psids)
                         ->where('problems.name', 'like', '%'.$request->name.'%')
                         ->select('problems.id', 'problem_problemset.problemset_id')
                         ->orderBy('problems.id', 'asc')
                         ->orderBy('problem_problemset.problemset_id', 'asc')
                         ->paginate(self::SEARCH_PAGE_LIMIT)
                         ->withQueryString();
    }

    $problems = \App\Problem::whereIn('id', array_map(function($key){return $key->id;}, $res->all()))->with('tags')->get();
    $problemsets = \App\Problemset::whereIn('id', array_map(function($key){return $key->problemset_id;}, $res->all()))->get();

    $problem_by_ids = array_by_id($problems);
    $problemset_by_ids = array_by_id($problemsets);

    return view('problem_search', [
      "result" => $res,
      "problems" => $problem_by_ids,
      "problemsets" => $problemset_by_ids,
    ]);
  }

  private function searchUser($request){
    $users = \App\User::where('name', 'like', '%'.$request->name.'%')
                      ->orWhere('nickname', 'like', '%'.$request->name.'%')
                      ->take(self::SEARCH_PAGE_LIMIT)
                      ->get();
    return view('user_search', [
        "users" => $users,
    ]);
  }

  public function search(Request $request){
    $this->validate($request, [
      'tags' => 'array',
      'search_item' => 'in:problems,users',
    ]);
    if($request->search_item === 'problems') return $this->searchProblem($request);
    else return $this->searchUser($request);
  }

  public function sourceCompare(Request $request){
    $lsolution = \App\Solution::findOrFail($request->lsid);
    $rsolution = \App\Solution::findOrFail($request->rsid);
    return view('source_compare',[
      'lsolution' => $lsolution,
      'rsolution' => $rsolution,
    ]);
  }

  public function getDiyPage($url){
    $diyPage = Cache::tags(['diyPages'])->rememberForever($url, function() use($url){
      return \App\DiyPage::where('url', $url)->first();
    });
    if($diyPage){
      return view('diy_page', [
        'diyPage' => $diyPage,
      ]);
    }else{
      abort(404);
    }
  }

  public function ide(){
    return view('ide');
  }

  public function simpleJudge(Request $request){
    $judgers = \App\Judger::where("ip_addr", "!=", "")->get();
    foreach($judgers as $judger){
      $client = new \WJudger\WJudgerClient($judger->ip_addr.":9717", [
          'credentials' => \Grpc\ChannelCredentials::createInsecure(),
      ]);

      if($client->waitForReady(1000000)){
      }else{
        continue;
      }

      $args = new \WJudger\SimpleArgs();
      $args->setJudgerid(0); //TODO
      $args->setToken($judger->token);
      $args->setLanguage($request->language);
      $args->setCode($request->code);
      $args->setInput($request->input);

      list($reply, $status) = $client->Simple($args)->wait();

      if($status->code){
        continue;
      }

      if($reply->getStatus() != \WJudger\JudgeStatus::OK){
        continue;
      }

      $result = [
        'status' => $reply->getStatus(),
        'compileError' => $reply->getCompileError(),
        'compileErrorMessage' => $reply->getCompileErrorMessage(),
        'runtimeError' => $reply->getRuntimeError(),
        'runtimeErrorMessage' => $reply->getRuntimeErrorMessage(),
        'timeused' => $reply->getTimeused(),
        'memoryused' => $reply->getMemoryused(),
        'output' => $reply->getOutput(),
      ];

      return response()->json($result);
    }
    return response()->json(['msg' => 'no judgers found'], 422);
  }
}
