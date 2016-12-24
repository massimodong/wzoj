<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminImportProblemsController extends Controller
{
	public function getImportProblems(){
		return view('admin.import_problems');
	}

	public function postImportProblems(Request $request){
		$this->validate($request, [
			'fps' => 'required|mimes:xml',
		]);
		return $request->file('fps')->getRealPath();
	}

}
