<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Gate;

class AdminAjaxController extends Controller
{
	public function getProblemsetProblems(Request $request){
		$this->validate($request, [
			'problemset_id' => 'required|integer',
		]);
		$problemset = \App\Problemset::findOrFail($request->problemset_id);

		if(Gate::denies('view', $problemset)){
			return response()->json([]);
		}

		return response()->json($problemset->problems()->orderByIndex()->get(['id', 'name']));
	}
}
