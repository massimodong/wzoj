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

class HomeController extends Controller
{
	const USER_LIMIT = 100;
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

		if(!(Auth::check())){
			$recent_problemsets = Cache::tags(['wzoj'])->remember('recent_problemsets', 1, function(){
				return \App\Problemset::where('type', '=', 'set')
							->where('public', true)
							->orderBy('updated_at', 'desc')
							->take(6)->get();
			});
		}else{
			$recent_problemsets = $request->user()->problemsets();
			usort($recent_problemsets, function($a, $b){
				return $a->updated_at < $b->updated_at;
			});
		}
		$recent_contests = Cache::tags(['wzoj'])->remember('recent_contests', 1, function(){
			return \App\Problemset::where('type','<>', 'set')->orderBy('contest_start_at', 'desc')->take(6)->get();
		});
		$home_page_problemsets=[];
		foreach($recent_problemsets as $problemset){
			if($problemset->type == 'set'){
				array_push($home_page_problemsets,$problemset);
			}
		}

		$top_users = Cache::tags(['wzoj'])->remember('top_users', 1, function(){
			return User::orderBy('cnt_ac', 'desc')->take(10)->withoutAdmin()->get();
		});

		$groups = [];
		if(Auth::check()){
			$user = $request->user();
			$groups = Cache::tags(['user_groups'])->rememberForever($user->id, function() use($user){
				return $user->groups;
			});
		}

		//homeworks
		$homework_flag = false;
		if(Auth::check()){
			$problem_cols = array();
			$problem_max_scores = array();

			foreach($request->user()->problemsets() as $problemset){
				$problem_cols[$problemset->id] = collect(new \App\Problem);
			}

			foreach($groups as $group){
				$homeworks = Cache::tags(['group_homeworks'])->rememberForever($group->id, function() use($group){
					return $group->homeworks;
				});
				foreach($homeworks as $problem){
					$problem_cols[$problem->pivot->problemset_id]->push($problem);
					$homework_flag = true;
				}
			}

			foreach($problem_cols as $problems) if(!$problems->isEmpty()){
				$psid = $problems[0]->pivot->problemset_id;
				$problem_max_scores[$psid] = $request->user()->max_scores($psid, $problems);
			}
		}

		return view('home',[
			'home_diy' => $diyPage,
			'home_page_problemsets' => $home_page_problemsets,
			'recent_contests' => $recent_contests,
			'top_users' => $top_users,
			'homework_problem_cols' => $homework_flag?$problem_cols:NULL,
			'homework_problem_max_scores' => $homework_flag?$problem_max_scores:NULL,
			'groups' => $groups,
		]);
	}

	public function ranklist(Request $request){
		$this->validate($request, ['page' => 'integer']);
		$page = 1;
		if(isset($request->page)) $page = $request->page;
		$users = User::orderBy('cnt_ac', 'desc')
				->skip(($page - 1) * self::USER_LIMIT)
				->take(self::USER_LIMIT)
				->withoutAdmin()
				->get();
		return view('ranklist', [
				'users' => $users,
				'start_rank' => ($page-1) * self::USER_LIMIT,
				'cur_page' => $page,
				'max_page' => (User::count()-1) / self::USER_LIMIT + 1]);
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

	public function problemSearch(Request $request){
		$this->validate($request, [
			'tags' => 'array',
		]);

		if(strlen($request->name) == 0 && count($request->tags) == 0){
			return back()
			    ->withErrors(trans('wzoj.no_search'))
			    ->withInput();
		}

		$problemsets_id = [];
		$problemset_ids = [];

		$groups = $request->user()->groups()->with('problemsets')->get();
		foreach($groups as $group){
			foreach($group->problemsets as $problemset){
				if(!isset($problemsets_id[$problemset->id])){
					$problemsets_id[$problemset->id] = $problemset;
					array_push($problemset_ids, $problemset->id);
				}
			}
		}
		$public_problemsets = \App\Problemset::where('public', true)->get();
		foreach($public_problemsets as $problemset){
			if(!isset($problemsets_id[$problemset->id])){
				$problemsets_id[$problemset->id] = $problemset;
				array_push($problemset_ids, $problemset->id);
			}
		}

		$problemsets = \App\Problemset::whereIn('id', $problemset_ids)
			->with(['problems' => function($query) use($request){
				$query->with('tags');
				if(isset($request->tags)){
					foreach($request->tags as $tag_id){
						$query->whereIn('id', function($q) use($tag_id){
							$q->select('problem_id')
							->from('problem_problem_tag')
							->where('problem_tag_id', $tag_id);
						});
					}
				}
				if(!empty($request->name)){
					$query->where('name', 'like', '%'.$request->name.'%');
				}
			}])->get();
		$problems = collect(new \App\Problem);
		foreach($problemsets as $problemset){
			foreach($problemset->problems as $problem){
				$problems->push($problem);
			}
		}
		if(count($problems) == 0){
			return back()
			    ->withErrors(trans('wzoj.no_result'))
			    ->withInput();

		}
		return view('problem_search', [
				'problems' => $problems,
				'problemsets' => $problemsets_id,
		]);
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

	public function getTagsChart(){
		return view('problem_tags_chart', [
			'tags' => \App\ProblemTag::all(),
		]);
	}
}
