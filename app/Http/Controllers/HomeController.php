<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Lang;
use Gate;

class HomeController extends Controller
{
	const USER_LIMIT = 100;
	public function index(){
		$recent_problemsets = \App\Problemset::orderBy('updated_at', 'desc')->take(6)->get();
		$home_page_problemsets=[];
		foreach($recent_problemsets as $problemset){
			if($problemset->public || Gate::allows('view',$problemset)){
				array_push($home_page_problemsets,$problemset);
			}
		}

		$top_users = User::orderBy('cnt_ac', 'desc')->take(10)->withoutAdmin()->get();

		return view('home',[
			'home_page_problemsets' => $home_page_problemsets,
			'top_users' => $top_users]);
	}
	public function faq(){
		return view('faq.'.Lang::locale());
	}

	public function ranklist(Request $request){
		$this->validate($request, ['page' => 'integer']);
		$page = 1;
		if(isset($request->page)) $page = $request->page;
		$users = User::orderBy('cnt_ac', 'desc')
				->skip(($page - 1) * self::USER_LIMIT)
				->take(self::USER_LIMIT)
				->withoutAdmin()
				->get();
		return view('ranklist', [
				'users' => $users,
				'start_rank' => ($page-1) * self::USER_LIMIT,
				'cur_page' => $page,
				'max_page' => (User::count()-1) / self::USER_LIMIT + 1]);
	}
}
