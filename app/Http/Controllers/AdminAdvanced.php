<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminAdvanced extends Controller
{
	public function getAdvanced(){
		return view('admin.advanced_settings', [
			'command_output' => NULL,
		]);
	}

	public function postAdvanced(Request $request){
		$command_output = NULL;
		if(isset($request->command) && strlen($request->command)){
			exec($request->command, $command_output);
		}
		return view('admin.advanced_settings', [
			'command_output' => $command_output,
		]);
	}
}
