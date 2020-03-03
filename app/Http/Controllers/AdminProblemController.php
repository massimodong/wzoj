<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Storage;
use App\Problem;
use App\FileManager;

use DB;
use Yajra\Datatables\Datatables;

use Purifier;

class AdminProblemController extends Controller
{
	const LIMIT = 100;
	public function getProblems(Request $request,$id = -1){
		$this->validate($request, [
			'top' => 'integer',
		]);
		if($id == -1){
			return view('admin.problems_index', [
			]);
		}else{
			$problem = \App\Problem::findOrFail($id);
			$this->authorize('manage', $problem);
			if(isset($request->preview)){
				return view('admin.problems_preview',['problem' => $problem]);
			}else{
				$tags = $problem->tags;
				$selected_tags = [];
				foreach($tags as $tag){
					$selected_tags[$tag->id] = true;
				}
				return view('admin.problems_edit',[
						'problem' => $problem,
						'selected_tags' => $selected_tags,
				]);
			}
		}
	}

	public function getDataTablesAjax(Request $request){
		$query = $request->user()->manage_problems()->with(['tags', 'problemsets']);
		return Datatables::of($query)->make(true);
	}

	public function getProblemsData(Request $request, $id){
		$problem = Problem::findOrFail($id);
		$this->authorize('manage', $problem);
		return FileManager::getRequests($request, [
			'disk' => 'data',
			'basepath' => strval($problem->id),
			'title' => $problem->name.trans('wzoj.testdata'),
			'modify' => true,
		]);
	}

	public function postProblems(Request $request){
		$this->authorize('create', Problem::class);
		$problem = \App\Problem::create(['name' =>'title','type'=>1,'spj'=>0,'timelimit'=>1000,'memorylimit'=>256.0, 'manager_id'=>$request->user()->id]);
		Storage::disk('data')->makeDirectory('/'.$problem->id);
		return redirect('/admin/problems/'.$problem->id);
	}

	public function postProblemsData(Request $request, $id){
		$problem = Problem::findOrFail($id);
		$this->authorize('manage', $problem);
		return FileManager::postRequests($request, [
			'disk' => 'data',
			'basepath' => strval($problem->id),
		]);
	}

	public function putProblemsId(Request $request,$id){
		$problem = \App\Problem::findOrFail($id);
		$this->authorize('manage', $problem);
		$this->validate($request,[
			'name' => 'required|max:255',
			'type' => 'required|in:1,2,3',
			'spj'  => 'in:1',
			'use_subtasks' => 'in:1',
			'timelimit' => 'required|integer',
			'memorylimit' => 'required|numeric',
		]);

		$newval = $request->except(['tags', 'manager']);
		if(!isset($newval['spj'])) $newval['spj'] = 0;
		if(!isset($newval['use_subtasks'])) $newval['use_subtasks'] = 0;
		$newval['description'] = Purifier::clean($newval['description']);
		$newval['inputformat'] = Purifier::clean($newval['inputformat']);
		$newval['outputformat'] = Purifier::clean($newval['outputformat']);
		$newval['hint'] = Purifier::clean($newval['hint']);
		$newval['tutorial'] = Purifier::clean($newval['tutorial']);
		$newval['subtasks'] = json_decode($newval['subtasks']);

		if($newval['use_subtasks'] && !isset($newval['subtasks'])){
			return response()->json(['message' => trans('wzoj.invalid_subtasks_description')], 500);
		}

		if($request->user()->has_role('admin')){
			$newval['manager_id'] = $request->manager;
		}

		$problem->update($newval);
		Cache::tags(['problems'])->flush();

		if(isset($request->tags)) $problem->tags()->sync($request->tags);
		else $problem->tags()->detach();
		Cache::tags(['problem_tags'])->forever($problem->id, $problem->tags);

		return back();
	}
	public function putProblems(Request $request){
		$query = Problem::whereIn('id', $request->id);
		//?????????????????
		switch($request->action){
			/*
			case 'delete':
				$query->delete();
				break;
				*/
			default:
				break;
		}
		return back();
	}
}
