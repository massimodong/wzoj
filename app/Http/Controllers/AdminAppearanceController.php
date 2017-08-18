<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sidebar;

class AdminAppearanceController extends Controller
{
	public function getAppearance(){
		$sidebars = Sidebar::all();
		return view('admin.appearance',[
			'sidebars' => $sidebars,
		]);
	}

	public function postSidebar(Request $request){
		$this->validate($request, [
			'sidebar_name' => 'required',
			'url' => 'required',
		]);
		$sidebar = Sidebar::create(['name' => $request->sidebar_name, 'url' => $request->url]);
		return back();
	}

	public function putSidebar(Request $request, $id){
		Sidebar::where('id', $id)
			->update($request->except(['_token', '_method']));
		return back();
	}

	public function deleteSidebar(Request $request, $id){
		$sidebar = Sidebar::findOrFail($id);
		$sidebar->delete();
		return back();
	}
}
