<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sidebar;
use App\DiyPage;

use Cache;

class AdminAppearanceController extends Controller
{
	public function getAppearance(){
		$sidebars = Sidebar::all();
		$diyPages = DiyPage::all();
		return view('admin.appearance',[
			'sidebars' => $sidebars,
			'diyPages' => $diyPages,
		]);
	}

	public function postSidebar(Request $request){
		$this->validate($request, [
			'sidebar_name' => 'required',
			'url' => 'required',
		]);
		$sidebar = Sidebar::create(['name' => $request->sidebar_name, 'url' => $request->url]);
		Cache::tags(['wzoj'])->forget('sidebars');
		return back();
	}

	public function putSidebar(Request $request, $id){
		Sidebar::where('id', $id)
			->update($request->except(['_token', '_method']));
		Cache::tags(['wzoj'])->forget('sidebars');
		return back();
	}

	public function deleteSidebar(Request $request, $id){
		$sidebar = Sidebar::findOrFail($id);
		$sidebar->delete();
		Cache::tags(['wzoj'])->forget('sidebars');
		return back();
	}

	public function postDiyPages(){
		$diyPage = DiyPage::create(['name' => 'title', 'url' => 'url', 'content' => '']);
		Cache::tags(['diyPages'])->forget('url');
		return redirect('/admin/appearance/diy-pages/'.$diyPage->id);
	}

	public function getDiyPages($id){
		$diyPage = DiyPage::findOrFail($id);
		return view('admin.diy_page_edit', [
			'diyPage' => $diyPage,
		]);
	}

	public function putDiyPages(Request $request, $id){
		DiyPage::where('id', $id)
			->update($request->except(['_token', '_method']));
		Cache::tags(['diyPages'])->forget($request->url);
		return back();
	}
}
