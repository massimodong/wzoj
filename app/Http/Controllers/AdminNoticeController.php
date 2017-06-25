<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminNoticeController extends Controller
{
	public function getNotice(){
		return view('admin.notice');
	}
}
