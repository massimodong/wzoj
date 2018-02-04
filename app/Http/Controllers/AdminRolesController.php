<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;
use DB;

class AdminRolesController extends Controller
{
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

		if($role->name === 'admin'){
			abort(403);
		}

		if($user->roles->map(function($item, $key){return $item->id;})->search($role->id) !== false){
			DB::table('role_user')
				->where('role_id', $role->id)
				->where('user_id', $user->id)
				->update(['remark' => $request->remark]);
			return back();
		}

		$user->roles()->attach($role->id, ['remark' => $request->remark]);
		return back();
	}

	public function deleteIndex(Request $request){
		$user = User::findOrFail($request->user_id);
		$role = Role::findOrFail($request->role_id);

		if($role->name === 'admin'){
			abort(403);
		}

		$user->roles()->detach($role->id);
		return back();
	}
}
