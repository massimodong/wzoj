<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;

use DB;
use App\Problemset;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProblemsetController extends Controller
{
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
		if(ojCanViewProblems($problemset)){
			$problems = $problemset->problems()->orderBy('problem_problemset.index','asc');
			$problems = $problems->get();
		}else{
			$problems = [];
		}

		return view('problemsets.view_'.$problemset->type,['problemset' => $problemset,'problems' => $problems]);
	}

	public function getRanklist($psid, Request $request){
		$problemset = Problemset::findOrFail($psid);
		//ranklist requires no authorization
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
		}
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

		return view('problemsets.edit',['problemset' => $problemset,'problems' => $problems]);
	}

	public function putProblemset($psid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$this->validate($request,[
			'name' => 'required|max:255',
			'type' => 'required|in:set,oi,acm,apio',
			'public' => 'in:1',
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
	public function getProblem($psid,$pid){
		$problemset = Problemset::findOrFail($psid);
		if(!$problemset->public){
			$this->authorize('view',$problemset);
		}

		$problem = $problemset->problems()->findOrFail($pid);
		if(!ojCanViewProblems($problemset)) return redirect('/s/'.$psid);

		return view('problems.view_'.$problemset->type,['problemset' => $problemset,'problem' => $problem]);
	}

	public function postProblem($psid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$this->validate($request,[
			'pid' => 'required|exists:problems,id|unique:problem_problemset,problem_id,NULL,id,problemset_id,'.$psid,
		]);

		$newindex = DB::table('problem_problemset')->where('problemset_id',$psid)
			->max('index')+1;
		$problemset->problems()->attach($request->pid,['index' => $newindex]);
		return back();
	}

	public function putProblem($psid,$pid,Request $request){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

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

		return back();
	}

	public function deleteProblem($psid,$pid){
		$problemset = Problemset::findOrFail($psid);
		$this->authorize('update',$problemset);

		$index = $problemset->problems()->findOrFail($pid)->pivot->index;

		$problemset->problems()->detach($pid);

		DB::table('problem_problemset')->where('problemset_id',$psid)
			->where('index','>',$index)
			->decrement('index',1);
		return back();
	}

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
}
