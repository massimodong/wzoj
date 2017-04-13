<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use Validator;

use DB;
use App\Problemset;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Auth;
use Storage;

class RanklistUser{
	public $user;
	public $score;
	public $penalty;
	public $problem_scores;
	public $problem_solutions;
	function __construct($u, $p){
		$this->user = $u;
		$this->score = 0;
		$this->penalty = 0;
		foreach($p as $s){
			$this->problem_scores[$s->id] = 0;
		}
	}
}

class ProblemsetController extends Controller
{
	const PAGE_LIMIT = 100;
	public function getIndex(){
		$allproblemsets = Problemset::where('type', '=', 'set')->get();
		$problemsets=[];
		foreach($allproblemsets as $problemset){
			if($problemset->public || Gate::allows('view',$problemset)){
				array_push($problemsets,$problemset);
			}
		}
		return view('problemsets.index',['problemsets' => $problemsets]);
	}
	public function getContestsIndex(){
		$contests = Problemset::where('type', '<>', 'set')->orderBy('contest_start_at', 'desc')->get();
		return view('problemsets.contests',['problemsets' => $contests]);
	}

	public function getProblemset($psid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		if(!$problemset->public){
			if(Gate::denies('view', $problemset)){
				if(Auth::check()){
					//todo
					abort(403);
				}else{
					return redirect('/auth/login');
				}
			}
		}

		$page = $cnt_pages = NULL;
		if(ojCanViewProblems($problemset)){
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

			$problems = Cache::tags(['problemsets', 'problems', $psid])->rememberForever($page, 
					function() use($problemset, $page){
				return $problemset->problems()->orderByIndex()
					->where('problem_problemset.index', '>', ($page-1) * self::PAGE_LIMIT)
					->where('problem_problemset.index', '<=', $page * self::PAGE_LIMIT)
					->get();
			});
		}else{
			$problems = [];
		}

		return view('problemsets.view_'.$problemset->type,[
				'problemset' => $problemset,
				'problems' => $problems,
				'max_scores' => Auth::check()?Auth::user()->max_scores($problemset, $problems):[],
				'cnt_pages' => $cnt_pages,
				'cur_page' => $page]);
	}

	public function getRanklist($psid, Request $request){
		$problemset = Problemset::findOrFail($psid);
		if($problemset->type == 'set' || time() < strtotime($problemset->contest_start_at)){
			//if not contest or contest not started
			return back();
		}
		$problems = $problemset->problems()->orderByIndex()->get();
		//ranklist requires no authorization

		$solutions = $problemset->solutions()
			->where('created_at', '>=', $problemset->contest_start_at)
			->where('created_at', '<=', $problemset->contest_end_at)
			->public()
			->get();

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
				$table[$id]->score -= $table[$id]->problem_scores[$solution->problem_id];
				$table[$id]->problem_scores[$solution->problem_id] = $solution->score;
				$table[$id]->score += $table[$id]->problem_scores[$solution->problem_id];

				$table[$id]->problem_solutions[$solution->problem_id] = $solution;
			}
		}
		usort($table, "ranklist_cmp_user");

		return  view('problemsets.ranklist', ['problemset' => $problemset,
						'problems' => $problems,
						'table' => $table,
						'last_solution_id' => $problemset->solutions()->max('id')]);
	}

	public function postNewProblemset(){
		$this->authorize('create',Problemset::class);
		$problemset = Problemset::create(['name'=>'problemset name','type'=>'set','public'=>'1']);
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
			'contest_start_at' => 'required',
			'contest_end_at' => 'required',
		]);

		$newval = $request->all();
		if(!isset($newval['public'])) $newval['public'] = 0;

		$problemset->update($newval);
		return back();
	}

	//problems
	public function getProblem($psid, $pid, Request $request){
		$problemset = Problemset::findOrFail($psid);
		if(!$problemset->public){
			$this->authorize('view',$problemset);
		}

		$problem = Cache::tags(['problems', $problemset->id])->rememberForever($pid, function() use($problemset, $pid){
			return $problemset->problems()->findOrFail($pid);
		});
		if(!ojCanViewProblems($problemset)) return redirect('/s/'.$psid);

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

		$download_url = NULL;
		if(Storage::disk('data')->has('/'.$problem->id.'/'.'download.zip')){
			$download_url = '/s/'.$problemset->id.'/'.$problem->id.'?download_attached_file=true';
		}
		return view('problems.view_'.$problemset->type,['problemset' => $problemset,
				'problem' => $problem,
				'answerfiles' => $answerfiles,
				'download_url' => $download_url]);
	}

	public function postProblem($psid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$pids = $request->pids;
		sort($pids);
		//for($i = count($request->pids)-1;$i >= 0;--$i){
		for($i = 0;isset($pids[$i]);++$i){
			$pid = $pids[$i];
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
		return back();
	}

	public function deleteGroup($psid, $gid){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$problemset->groups()->detach($gid);

		return back();
	}
}
