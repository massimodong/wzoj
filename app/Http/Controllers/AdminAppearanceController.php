<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminAppearanceController extends Controller
{
	public function getAppearance(){
		return view('admin.appearance');
	}
}
