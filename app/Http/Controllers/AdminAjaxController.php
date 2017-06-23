<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminAjaxController extends Controller
{
	public function getProblemsetProblems(Request $request){
		$this->validate($request, [
			'problemset_id' => 'required|integer',
		]);
		$problemset = \App\Problemset::findOrFail($request->problemset_id);
		return response()->json($problemset->problems()->orderByIndex()->get(['id', 'name']));
	}
}
