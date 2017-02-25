<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;
use App\Problem;

class AdminProblemController extends Controller
{
	const LIMIT = 20;
	public function getProblems(Request $request,$id = -1){
		$this->validate($request, [
			'top' => 'integer',
		]);
		if($id == -1){
			/*
			$top = 1;
			if(isset($request->top)){
				$top = $request->top;
			}

			$prev = max($top - self::LIMIT, 1);
			$prev_url = '/admin/problems?top='.$prev;
			if($top == 1){
				$prev_url= '';
			}

			$next = \App\Problem::where('id', '>=', $top)->skip(self::LIMIT)->first();
			if($next){
				$next_url = '/admin/problems?top='.$next->id;
			}else{
				$next_url = '';
			}

			$bottom = max(\App\Problem::count() - self::LIMIT + 1, 1);
			$bottom_url = '/admin/problems?top='.$bottom;

			$problems = \App\Problem::where('id', '>=', $top)->orderBy('id', 'asc')->take(self::LIMIT)->get();
			return view('admin.problems_index',['problems' => $problems,
					'prevpage_url' => $prev_url,
					'nextpage_url' => $next_url,
					'bottompage_url' => $bottom_url]);
					*/
			$problems = Problem::with('problemsets')->get();
			return view('admin.problems_index', ['problems' => $problems]);
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
		Storage::disk('data')->makeDirectory('/'.$problem->id);
		return redirect('/admin/problems/'.$problem->id);
	}

	public function putProblemsId(Request $request,$id){
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
	public function putProblems(Request $request){
		$query = Problem::whereIn('id', $request->id);
		switch($request->action){
			case 'delete':
				$query->delete();
				break;
		}
		return back();
	}
	public function deleteProblems($id){
		$problem = \App\Problem::findOrFail($id);
		$problem->delete();
		return redirect('/admin/problems');
	}

}
