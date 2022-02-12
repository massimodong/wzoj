<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use Validator;

use App\Jobs\updateProblemStatus;

use DB;
use App\Problemset;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Auth;
use Storage;
use Purifier;

class RanklistUser{
  public $user;
  public $rank;
  public $score;
  public $penalty;
  public $problem_scores;
  public $problem_corrected_scores;
  public $problem_solutions;
  function __construct($u, $p, $score = 0){
    $this->user = $u;
    $this->score = $score;
    $this->penalty = 0;
    foreach($p as $s){
      $this->problem_scores[$s->id] = -1;
      $this->problem_corrected_scores[$s->id] = -1;
    }
  }
}

class ProblemsetController extends Controller
{
  const PAGE_LIMIT = 100;
  public function getIndex(Request $request){
    if(!(Auth::check())){
      $allproblemsets = Problemset::where('type', '=', 'set')
                                  ->where('public', true)
                                  ->get();
    }else{
      $allproblemsets = $request->user()->problemsets()->sortBy('id');
    }
    $problemsets = collect();
    foreach($allproblemsets as $problemset){
      if($problemset->type == 'set'){
        $problemsets->push($problemset);
      }
    }
    return view('problemsets.index',[
      'problemsets' => $problemsets,
    ]);
  }
  public function getContestsIndex(Request $request){
    if(!(Auth::check())){
      $allcontests = Problemset::where('type', '<>', 'set')
                               ->where('public', true)
                               ->orderBy('contest_start_at', 'desc');
      if(!empty(\Request::get('contests'))){
        $allcontests = $allcontests->whereIn('id', \Request::get('contests'));
      }

      $allcontests = $allcontests->get();
    }else{
      $allcontests = $request->user()->problemsets()->sortByDesc('contest_start_at');
    }

    $contests = collect();
    $tag = NULL;
    if(isset($request->tag)) $tag = $request->tag;
    foreach($allcontests as $problemset){
      if($problemset->type != 'set'){
        if($tag && $problemset->tag != $tag) continue;
        $contests->push($problemset);
      }
    }
    return view('problemsets.contests',[
        'problemsets' => $contests,
        'tag' => $tag,
    ]);
  }

  public function getProblemset($psid,Request $request){
    $problemset = Problemset::findOrFail($psid);
    if(!$problemset->public){
      if(Gate::denies('view', $problemset)){
        if(Auth::check()){
          abort(403);
        }else{
          return redirect('/auth/login');
        }
      }
    }

    $vp = false;
    if(Auth::check()){
      $vp = Auth::user()->virtual_participations()
                        ->where('problemset_id', $problemset->id)
                        ->orderBy('id', 'desc')
                        ->first();
      if(!isset($vp)) $vp = false;
    }

    $page = $cnt_pages = NULL;
    if(ojCanViewProblems($problemset, $vp)){
      //select page
      $page = 1;
      $cnt_problems = Cache::tags(['problemsets', 'cnt_problems'])
          ->rememberForever($psid, function() use($problemset){
        return $problemset->problems()->count();
      });
      $cnt_pages = (($cnt_problems-1) / self::PAGE_LIMIT) + 1;
      if(isset($request->page)){
        $page = $request->page;
      }

      $problems = Cache::tags(['problemsets', 'problems', $psid])->remember($page, CACHE_ONE_DAY,
          function() use($problemset, $page){
        return $problemset->problems()->orderByIndex()
          ->where('problem_problemset.index', '>', ($page-1) * self::PAGE_LIMIT)
          ->where('problem_problemset.index', '<=', $page * self::PAGE_LIMIT)
          ->with(['tags'])
          ->leftJoin('problem_statistics', function($join) use($problemset){
              $join->on('problems.id', 'problem_statistics.problem_id')
                   ->where('problem_statistics.problemset_id', '=', $problemset->id);
            })
          ->select(['problems.*', 'problem_statistics.*'])
          ->get();
      });
    }else{
      $problems = collect();
    }

    $problem_ids = $problems->map(function($item, $key){
      return $item->id;
    })->toArray();

    $contest_start_time = strtotime($vp ? $vp->contest_start_at : $problemset->contest_start_at);
    $contest_end_time = strtotime($vp ? $vp->contest_end_at : $problemset->contest_end_at);
    $cur_time = time();
    $contest_period = CONTEST_PENDING;
    if($cur_time > $contest_start_time) $contest_period = CONTEST_RUNNING;
    if($cur_time > $contest_end_time) $contest_period = CONTEST_ENDED;

    return view('problemsets.view_'.$problemset->type,[
        'problemset' => $problemset,
        'problems' => $problems,
        'max_scores' => Auth::check()?max_scores([Auth::user()->id], [$problemset->id], $problem_ids)[Auth::user()->id][$problemset->id]:[],
        'cnt_pages' => $cnt_pages,
        'cur_page' => $page,
        'virtual_participation' => $vp,
        'contest_start_time' => $contest_start_time,
        'contest_end_time' => $contest_end_time,
        'contest_period' => $contest_period,
    ]);
  }

  public function getRanklistTableRaw($problemset, $problems, $contest_running){
    $solutions = collect();
    switch($problemset->participate_type){
      case 0:
        $solutions = $problemset->solutions()
          ->where('created_at', '>=', $problemset->contest_start_at)
          ->where('created_at', '<=', $problemset->contest_end_at)
          ->with(['user'])
          ->get(['id', 'user_id', 'problem_id', 'problemset_id', 'score', 'status', 'ce']);
        break;
      case 1:
        $solutions = $problemset->solutions()
          ->join('virtual_participations', 'solutions.user_id', '=', 'virtual_participations.user_id')
          ->where('virtual_participations.problemset_id', $problemset->id)
          ->where('virtual_participations.contest_start_at', '>=', $problemset->contest_start_at)
          ->where('virtual_participations.contest_start_at', '<=', $problemset->contest_end_at)
          ->whereRaw('solutions.created_at >= virtual_participations.contest_start_at')
          ->whereRaw('solutions.created_at <= virtual_participations.contest_end_at')
          ->with(['user'])
          ->get(['solutions.id', 'solutions.user_id', 'solutions.problem_id', 'solutions.problemset_id', 'solutions.score', 'solutions.status', 'solutions.ce']);
        break;
      case 2:
        abort(400);
    }

    $table = array();
    $users_id = [];
    foreach($solutions as $solution){
      if(!isset($users_id[$solution->user_id])){
        array_push($table, new RanklistUser($solution->user, $problems));
        $users_id[$solution->user_id] = count($table)-1;
      }
      $id = $users_id[$solution->user_id];
      ++$table[$id]->penalty;

      if(isset($table[$id]->problem_scores[$solution->problem_id])){
        if($table[$id]->problem_scores[$solution->problem_id] >= 0){
          $table[$id]->score -= $table[$id]->problem_scores[$solution->problem_id];
          $table[$id]->problem_scores[$solution->problem_id] = $solution->score;
          $table[$id]->problem_corrected_scores[$solution->problem_id] = $solution->score;
          $table[$id]->score += $table[$id]->problem_scores[$solution->problem_id];
        }else{
          $table[$id]->problem_scores[$solution->problem_id] = $solution->score;
          $table[$id]->score += $table[$id]->problem_scores[$solution->problem_id];
        }
        $table[$id]->problem_solutions[$solution->problem_id] = $solution;
      }
    }

    if(!$contest_running){//ended
      $solutions = $problemset->solutions()
        ->with(['user'])
        ->get();
      foreach($solutions as $solution){
        if(!isset($users_id[$solution->user_id])){
          array_push($table, new RanklistUser($solution->user, $problems, -1));
          $users_id[$solution->user_id] = count($table)-1;
        }
        $id = $users_id[$solution->user_id];
        if(isset($table[$id]->problem_corrected_scores[$solution->problem_id])){
          if($solution->score > $table[$id]->problem_corrected_scores[$solution->problem_id])
            $table[$id]->problem_corrected_scores[$solution->problem_id] = $solution->score;
        }
      }
    }

    usort($table, "ranklist_cmp_user");

    $cnt = count($table);
    if($cnt > 0) $table[0]->rank = 0;
    for ($i = 1; $i < $cnt; $i++){
      if ($table[$i]->score == $table[$i-1]->score) $table[$i]->rank = $table[$i-1]->rank;
      else $table[$i]->rank = $i;
    }

    return $table;
  }

  public function getRanklistTable($problemset, $problems, $contest_running){
    return Cache::tags(['problemset_ranklist'])->rememberForever($problemset->id, function() use($problemset, $problems, $contest_running){
      $lock = Cache::lock('problemset_ranklist_'.$problemset->id, 10);
      try {
        $lock->block(15);
        return Cache::tags(['problemset_ranklist'])->rememberForever($problemset->id, function() use($problemset, $problems, $contest_running){
          return $this->getRanklistTableRaw($problemset, $problems, $contest_running);
        });
      } catch (LockTimeoutException $e) {
        abort(503);
      } finally {
        optional($lock)->release();
      }
    });
  }

  public function getRanklist($psid, Request $request){
    $problemset = Problemset::findOrFail($psid);
    if($problemset->type == 'set' || time() < strtotime($problemset->contest_start_at)){
      //if not contest or contest not started
      return back();
    }
    if(!$problemset->public){
      if(Gate::denies('view', $problemset)){
        if(Auth::check()){
          abort(403);
        }else{
          return redirect('/auth/login');
        }
      }
    }

    if($problemset->isHideSolutions()) abort(403);

    $contest_running = $problemset->isContestRunning();
    $problems = $problemset->problems()->orderByIndex()->get();
    $table = $this->getRanklistTable($problemset, $problems, $contest_running);
    return  view('problemsets.ranklist', ['problemset' => $problemset,
        'problems' => $problems,
        'table' => $table,
        'contest_running' => $contest_running,
    ]);
  }

  public function getRanklistCSV($psid, Request $request){
    $problemset = Problemset::findOrFail($psid);
    if($problemset->type == 'set' || time() < strtotime($problemset->contest_start_at)){
      //if not contest or contest not started
      return back();
    }
    
    download_send_headers($problemset->name . '-' . date("Y-m-d") . ".csv");

    $contest_running = $problemset->isContestRunning();
    $problems = $problemset->problems()->orderByIndex()->get();
    $table = $this->getRanklistTable($problemset, $problems, $contest_running);

    $df = fopen("php://output", "w");
    utf8_bom($df);

    $head=array(trans('wzoj.rank'),trans('wzoj.user'),trans('wzoj.fullname'),trans('wzoj.class'),trans('wzoj.score'));
    foreach($problems as $problem){
      array_push($head, $problem->name);
    }
    fputcsv($df, $head);

    $last_rank = 0;
    foreach($table as $index => $row){
      if($index > 0 && $table[$index-1]->score == $row->score){
        $rank = $last_rank;
      }else{
        $last_rank = $rank = $index + 1;
      }
      $item = array($rank, $row->user->name, $row->user->fullname, $row->user->class, $row->score);
      foreach($problems as $problem){
        if(isset($row->problem_solutions[$problem->id])){
          array_push($item, $row->problem_solutions[$problem->id]->score);
        }else{
          array_push($item, 0);
        }
      }
      fputcsv($df, $item);
    }

    fclose($df);
    return;
  }

  public function postNewProblemset(Request $request){
    $this->authorize('create',Problemset::class);

    $type = 'set';
    if(isset($request->type) && $request->type != "")  $type = $request->type;

    $problemset = Problemset::create(['name'=>'problemset name','type'=>$type,'public'=>'0', 'manager_id'=>$request->user()->id]);
    Cache::tags(['wzoj'])->forever('problemsets_last_updated_at', time());
    return redirect('/s/'.$problemset->id.'/edit');
  }

  public function getEditProblemset($psid){
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $problems = $problemset->problems()->orderBy('problem_problemset.index','asc');

    $problems = $problems->get();

    $gids = [];
    foreach($problemset->groups as $group){
      array_push($gids, $group->id);
    }
    $groups = \App\Group::whereNotIn('id', $gids)->get();

    return view('problemsets.edit',[
        'problemset' => $problemset,
        'problems' => $problems,
        'groups' => $groups]);
  }

  public function putProblemset($psid,Request $request){
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $this->validate($request,[
      'name' => 'required|max:255',
      'type' => 'required|in:set,oi,acm,apio',
      'public' => 'in:1',
      'show_problem_tags' => 'in:1',
      'participate_type' => 'in:0,1,2',
      'contest_duration' => 'integer',
    ]);

    $newval = $request->except(['manager']);

    if(!isset($newval['public'])) $newval['public'] = 0;
    if(!isset($newval['show_problem_tags'])) $newval['show_problem_tags'] = 0;
    if(!isset($newval['show_tutorial'])) $newval['show_tutorial'] = 0;
    if(!isset($newval['contest_hide_solutions'])) $newval['contest_hide_solutions'] = 0;
    if(!isset($newval['participate_type'])) $newval['participate_type'] = 0;
    if(!isset($newval['contest_duration'])) $newval['contest_duration'] = 120;

    $newval['description'] = Purifier::clean($newval['description']);
    $newval['contest_duration'] = $newval['contest_duration'] * 60;
    if($request->user()->has_role('admin')){
      $newval['manager_id'] = $request->manager;
    }

    $problemset->update($newval);

    Cache::tags(['wzoj'])->forever('problemsets_last_updated_at', time());
    return back();
  }

  public function postVirtualParticipate($psid, Request $request){
    $problemset = Problemset::findOrFail($psid);
    if(!$problemset->public){
      if(Gate::denies('view', $problemset)){
        if(Auth::check()){
          abort(403);
        }else{
          return redirect('/auth/login');
        }
      }
    }

    if($problemset->type == 'set') abort(400);

    switch($problemset->participate_type){
      case 0:
        abort(400);
      case 1:
        if(time() < strtotime($problemset->contest_start_at) || strtotime($problemset->contest_end_at) < time()) abort(400);
        break;
      case 2:
        abort(400);
        break;
    }

    $vp = Auth::user()->virtual_participations()
                      ->where('problemset_id', $problemset->id)
                      ->orderBy('id', 'desc')
                      ->first();

    if(isset($vp)) abort(400); //TODO: prevent multithreading attacks

    $vp = new \App\VirtualParticipation();
    $vp->problemset_id = $problemset->id;
    $vp->contest_start_at = date('Y-m-d H:i:s');
    $vp->contest_end_at = date('Y-m-d H:i:s', time() + $problemset->contest_duration); //TODO: virtual contest should end before real contest ends

    Auth::user()->virtual_participations()->save($vp);
    return redirect()->back();
  }

  //problems
  public function getProblem($psid, $pid, Request $request){
    $problemset = Problemset::findOrFail($psid);

    if(!ojCanViewProblems($problemset)) return redirect('/s/'.$psid);
    if(!$problemset->public){
      $this->authorize('view',$problemset);
    }

    $problem = Cache::tags(['problems', $problemset->id])->rememberForever($pid, function() use($problemset, $pid){
      return $problemset->problems()->findOrFail($pid);
    });

    if(isset($request->download_attached_file)){//download file, do not render page
      $storagePath = Storage::disk('data')->getDriver()->getAdapter()->getPathPrefix();
      return response()->download($storagePath.'/'.$problem->id.'/download.zip', $problem->id.'.zip');
    }

    $answerfiles = NULL;
    if($problem->type == 3 && Auth::check()){
      $answerfiles = $request->user()->answerfiles()
        ->where('problemset_id', $problemset->id)
        ->where('problem_id', $problem->id)
        ->get();
    }

    $has_test_data = Cache::tags(['problems', 'has_test_data'])->get($pid, false);
    if(!$has_test_data){
      $has_test_data = count(Storage::disk('data')->files('/'.$problem->id)) > 0;
      if($has_test_data){
        Cache::tags(['problems', 'has_test_data'])->put($pid, true, CACHE_ONE_MONTH);
      }
    }

    $download_url = NULL;
    if(Storage::disk('data')->has('/'.$problem->id.'/'.'download.zip')){
      $download_url = '/s/'.$problemset->id.'/'.$problem->id.'?download_attached_file=true';
    }

    $topics = Cache::tags(['problem_topics'])->remember($problem->id, CACHE_ONE_MINUTE, function() use($problem){
      return \App\ForumTopic::whereIn('id', function($query) use($problem){
          $query->select('forum_topic_id')
          ->from(with(new \App\ForumTag)->getTable())
          ->where('value', '=', 'p'.$problem->id);
        })
        ->orderBy('updated_at', 'desc')
        ->take(3)
        ->get();
    });

    if(Auth::check()){
      $view_history = Cache::tags(['wzoj'])->get('view_history.'.$request->user()->id, collect());
      if($view_history->filter(function($value) use($problemset, $problem, $request){
          return $value["psid"] == $problemset->id && $value["pid"] == $problem->id && $value["uid"] == $request->user()->id;
        })->count() == 0){
        $view_history->prepend(["psid" => $problemset->id, "pid" => $problem->id, "uid" => $request->user()->id, "pn" => $problem->name, "psn" => $problemset->name]);
        Cache::tags(['wzoj'])->put('view_history.'.$request->user()->id, $view_history->take(3), CACHE_ONE_MONTH);
      };
    }

    $cnt_submit = 0;
    $tot_score = 0;
    $cnt_ac = 0;
    if($problemset->type == "set"){
      $statistics = DB::select('SELECT count, score_sum, count_ac FROM problem_statistics WHERE problemset_id = ? AND problem_id = ?', [$problemset->id, $problem->id]);
      if(count($statistics) > 0){
        $cnt_submit = $statistics[0]->count;
        $tot_score = $statistics[0]->score_sum;
        $cnt_ac = $statistics[0]->count_ac;
      }
    }

    return view('problems.view_'.$problemset->type,['problemset' => $problemset,
        'problem' => $problem,
        'answerfiles' => $answerfiles,
        'download_url' => $download_url,
        'has_test_data' => $has_test_data,
        'cnt_submit' => $cnt_submit,
        'tot_score' => $tot_score,
        'cnt_ac' => $cnt_ac,
        'topics' => $topics,
    ]);
  }

  public function postProblem($psid,Request $request){
    DB::setDefaultConnection('mysql_write');
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $pids = $request->pids;
    sort($pids);
    //for($i = count($request->pids)-1;$i >= 0;--$i){
    for($i = 0;isset($pids[$i]);++$i){
      $pid = $pids[$i];

      $problem = \App\Problem::findOrFail($pid);
      if(Gate::denies('manage', $problem)) continue;

      $arr = [];
      $arr['pid'] = $pid;
      $validator = Validator::make($arr,[
        'pid' => 'required|exists:problems,id|unique:problem_problemset,problem_id,NULL,id,problemset_id,'.$psid,
      ]);
      if(!$validator->fails()) {
        $newindex = DB::table('problem_problemset')->where('problemset_id',$psid)
          ->max('index')+1;
        $problemset->problems()->attach($pid,['index' => $newindex]);
      }
    }
    Cache::tags(['problemsets', 'cnt_problems'])->forget($psid);
    Cache::tags([$psid])->flush();
    return back();
  }

  public function putProblem($psid,Request $request){
    DB::setDefaultConnection('mysql_write');
    $this->validate($request, [
      'newindex' => 'required|integer',
    ]);
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $pids = [];
    foreach($request->id as $index){
      $pid = DB::table('problem_problemset')->where('problemset_id', $psid)
        ->where('index', $index)
        ->select("problem_id")
        ->first();
      array_push($pids, $pid->problem_id);
    }
    //return count($pids);
    for($i = count($pids) - 1;$i >= 0;--$i){
      $pid = $pids[$i];

      $problem = $problemset->problems()->findOrFail($pid);
      $this->validate($request,[
        'newindex' => 'exists:problem_problemset,index,problemset_id,'.$psid,
      ]);

      if(isset($request->newindex) && $request->newindex>0){
        $index = $problem->pivot->index;
        if($request->newindex > $index){
          $problemset->problems()->detach($pid);
          DB::table('problem_problemset')->where('problemset_id',$psid)
            ->where('index','>',$index)
            ->where('index','<=',$request->newindex)
            ->decrement('index',1);
          $problemset->problems()->attach($pid,['index' => $request->newindex]);
        }else if($request->newindex < $index){
          $problemset->problems()->detach($pid);
          DB::table('problem_problemset')->where('problemset_id',$psid)
            ->where('index','<',$index)
            ->where('index','>=',$request->newindex)
            ->increment('index',1);
          $problemset->problems()->attach($pid,['index' => $request->newindex]);
        }
      }
    }

    Cache::tags([$psid])->flush();
    return back();
  }

  public function deleteProblem($psid, Request $request){
    DB::setDefaultConnection('mysql_write');
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $pids = [];
    foreach($request->id as $index){
      $pid = DB::table('problem_problemset')->where('problemset_id', $psid)
        ->where('index', $index)
        ->select("problem_id")
        ->first();
      array_push($pids, $pid->problem_id);
    }

    foreach($pids as $pid){
      $index = $problemset->problems()->findOrFail($pid)->pivot->index;

      $problemset->problems()->detach($pid);

      DB::table('problem_problemset')->where('problemset_id',$psid)
        ->where('index','>',$index)
        ->decrement('index',1);
    }
    Cache::tags(['problemsets', 'cnt_problems'])->forget($psid);
    Cache::tags([$psid])->flush();
    return back();
  }

  /*
  public function getSubmit($psid,$pid){
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('view',$problemset);
    $problem = $problemset->problems()->findOrFail($pid);
    if(ojCanViewProblems($problemset)){
      return view('problems.submit',['problemset' => $problemset, 'problem' => $problem]);
    }else{
      return redirect('/');
    }
  }
  */
  
  //groups
  public function postGroup($psid, Request $request){
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $this->validate($request,[ //buggy
      'gid' => 'exists:groups,id|unique:group_problemset,group_id,NULL,id,problemset_id,'.$psid,
    ]);

    $problemset->groups()->attach($request->gid);

    Cache::tags(['wzoj'])->forever('problemsets_last_updated_at', time());
    return back();
  }

  public function deleteGroup($psid, $gid){
    $problemset = Problemset::findOrFail($psid);
    $this->authorize('update',$problemset);

    $problemset->groups()->detach($gid);

    Cache::tags(['wzoj'])->forever('problemsets_last_updated_at', time());
    return back();
  }
}
