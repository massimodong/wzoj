<?php

namespace App\Http\Controllers;

use App\Events\Broadcast;
use Event;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminFunctionsController extends Controller
{
	public function getFunctions(){
		return view('admin.functions');
	}

	public function postBroadcast(Request $request){
		$this->validate($request, [
			'title' => 'required',
			'content' => 'required',
		]);
		Event::dispatch(new Broadcast($request->title, $request->content));
		return back();
	}
}
