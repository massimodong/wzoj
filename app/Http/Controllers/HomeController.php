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

		$recent_contests = Cache::tags(['wzoj'])->remember('recent_contests', 1, function(){
			return \App\Problemset::where('type','<>', 'set')->orderBy('contest_start_at', 'desc')->take(6)->get();
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

		$sidePanels = Cache::tags(['wzoj'])->rememberForever('sidepanels', function(){
			return \App\SidePanel::where('index', '>', 0)->orderBy('index', 'asc')->get();
		});

		return view('home',[
			'breadcrumb' => 'home',
			'home_diy' => $diyPage,
			'recent_contests' => $recent_contests,
			'group_homeworks' => $homework_flag?$group_homeworks:NULL,
			'groups' => $groups,
			'sidePanels' => $sidePanels,
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
