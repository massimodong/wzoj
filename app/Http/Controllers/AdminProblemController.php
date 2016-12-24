<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminProblemController extends Controller
{
	public function getProblems(Request $request,$id = -1){
		if($id == -1){
			$problems = \App\Problem::all();
			return view('admin.problems_index',['problems' => $problems]);
		}else{
			$problem = \App\Problem::findOrFail($id);
			if(isset($request->preview)){
				return view('admin.problems_preview',['problem' => $problem]);
			}else{
				return view('admin.problems_edit',['problem' => $problem]);
			}
		}
	}

	public function postProblems(){
		$problem = \App\Problem::create(['name' =>'title','type'=>1,'spj'=>0,'timelimit'=>1000,'memorylimit'=>256.0]);
		return redirect('/admin/problems/'.$problem->id);
	}

	public function putProblems(Request $request,$id){
		$problem = \App\Problem::findOrFail($id);
		$this->validate($request,[
			'name' => 'required|max:255',
			'type' => 'required|in:1,2,3',
			'spj'  => 'in:1',
			'timelimit' => 'required|integer',
			'memorylimit' => 'required|numeric',
		]);

		$newval = $request->all();
		if(!isset($newval['spj'])) $newval['spj'] = 0;

		$problem->update($newval);

		return back();
	}
	public function deleteProblems($id){
		$problem = \App\Problem::findOrFail($id);
		$problem->delete();
		return redirect('/admin/problems');
	}

}
