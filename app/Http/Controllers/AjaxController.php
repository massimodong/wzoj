<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cache;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Problemset;
use App\Jobs\updateProblemStatus;

class AjaxController extends Controller
{
	public function getProblemStatusRequest(Request $request){
		$this->validate($request,[
			'psid' => 'required|integer',
			'pid' => 'required|integer',
		]);

		$problemset = Problemset::findOrFail($request->psid);

		$hide_solutions = $problemset->isHideSolutions();
		if($hide_solutions) return response()->json(['ok' => False]);

		$problem = Cache::tags(['problems', $problemset->id])->rememberForever($request->pid, function() use($problemset, $request){
			return $problemset->problems()->findOrFail($request->pid);
		});

		$this->dispatch(new updateProblemStatus($problemset, $problem));

		return response()->json(['ok' => True]);
	}
}
