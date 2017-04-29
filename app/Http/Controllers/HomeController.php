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
	public function index(){
		if(!empty(\Request::get('contests'))){
			return redirect('/contests');
		}
		$recent_problemsets = Cache::tags(['wzoj'])->remember('recent_problemsets', 1, function(){
			return \App\Problemset::where('type', '=', 'set')->orderBy('updated_at', 'desc')->take(6)->get();
		});
		$recent_contests = Cache::tags(['wzoj'])->remember('recent_contests', 1, function(){
			return \App\Problemset::where('type','<>', 'set')->orderBy('contest_start_at', 'desc')->take(6)->get();
		});
		$home_page_problemsets=[];
		foreach($recent_problemsets as $problemset){
			if($problemset->public || Gate::allows('view',$problemset)){
				array_push($home_page_problemsets,$problemset);
			}
		}

		$top_users = Cache::tags(['wzoj'])->remember('top_users', 1, function(){
			return User::orderBy('cnt_ac', 'desc')->take(10)->withoutAdmin()->get();
		});

		return view('home',[
			'home_page_problemsets' => $home_page_problemsets,
			'recent_contests' => $recent_contests,
			'top_users' => $top_users]);
	}
	public function faq(){
		return view('faq.'.Lang::locale());
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
}
