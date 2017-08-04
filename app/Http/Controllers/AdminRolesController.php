<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;

class AdminRolesController extends Controller
{
	static function flushCache(){
	}

	public function getIndex(){
		$users = User::all();
		$roles = Role::all();
		return view('admin.roles',[
			'users' => $users,
			'roles' => $roles,
		]);
	}

	public function postIndex(Request $request){
		$user = User::findOrFail($request->user_id);
		$role = Role::findOrFail($request->role_id);

		if($user->roles->map(function($item, $key){return $item->id;})->search($role->id) !== false){
			return back();
		}

		if($role->name === 'admin'){
			abort(403);
		}

		$user->roles()->attach($role->id);
		return back();
	}

	public function deleteIndex(Request $request){
		$user = User::findOrFail($request->user_id);
		$role = Role::findOrFail($request->role_id);

		if($role->name === 'admin'){
			abort(403);
		}

		$user->roles()->detach($role->id);
		$this->flushCache();
		return back();
	}
}
