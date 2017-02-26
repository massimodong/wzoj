<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use Validator;

use DB;
use App\Problemset;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Storage;

class ProblemsetController extends Controller
{
	const PAGE_LIMIT = 100;
	public function getIndex(){
		$allproblemsets = Problemset::all();
		$problemsets=[];
		foreach($allproblemsets as $problemset){
			if($problemset->public || Gate::allows('view',$problemset)){
				array_push($problemsets,$problemset);
			}
		}
		return view('problemsets.index',['problemsets' => $problemsets]);
	}

	public function getProblemset($psid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		if(!$problemset->public){
			$this->authorize('view',$problemset);
		}

		$page = $cnt_pages = NULL;
		if(ojCanViewProblems($problemset)){
			$problems = $problemset->problems()->orderByIndex();

			//select page
			$page = 1;
			$cnt_pages = (($problemset->problems()->count()-1) / self::PAGE_LIMIT) + 1;
			if(isset($request->page)){
				$page = $request->page;
			}
			$problems=$problems->where('problem_problemset.index', '>', ($page-1) * self::PAGE_LIMIT);
			$problems=$problems->where('problem_problemset.index', '<=', $page * self::PAGE_LIMIT);

			$problems = $problems->get();
		}else{
			$problems = [];
		}

		return view('problemsets.view_'.$problemset->type,[
				'problemset' => $problemset,
				'problems' => $problems,
				'cnt_pages' => $cnt_pages,
				'cur_page' => $page]);
	}

	public function getRanklist($psid, Request $request){
		$problemset = Problemset::findOrFail($psid);
		$problems = $problemset->problems()->orderByIndex()->get();
		//ranklist requires no authorization

		//Pick the last solution for each user/problem
		$solutions = \App\Solution::whereIn('id', function($query) use($psid){
			$query->select(DB::raw('MAX(id) as id'))
			      ->from(with(new \App\Solution)->getTable())
			      ->where('problemset_id', $psid)
			      ->groupBy(['user_id', 'problem_id']);
		})->public()->get();

		return  view('problemsets.ranklist', ['problemset' => $problemset,
						'problems' => $problems,
						'solutions'  => $solutions,
						'last_solution_id' => $problemset->solutions()->max('id')]);
		/*
		switch($problemset->type){
			case 'set':
				//no ranklist for SET
				return redirect('/s/'.$psid);
				break;
			case 'oi':
				return view('problemsets.ranklist_oi', ['problemset' => $problemset]);
				break;
			default:
				abort(503);
				break;
		}*/
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

	public function deleteProblemset($psid){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$problemset->delete();
		return redirect('/s');
	}

	//problems
	public function getProblem($psid, $pid, Request $request){
		$problemset = Problemset::findOrFail($psid);
		if(!$problemset->public){
			$this->authorize('view',$problemset);
		}

		$problem = $problemset->problems()->findOrFail($pid);
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
