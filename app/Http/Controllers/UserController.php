<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\FileManager;

use Auth;
use Cache;
use DB;

class UserController extends Controller
{
	public function getId($id){
		$user = User::findOrFail($id);
		$groups = Cache::tags(['user_groups'])->rememberForever($user->id, function() use($user){
			return $user->groups;
		});
		$cnt_submissions = $user->solutions()->count();

		return view('user.profile',['user' => $user,
                                'cnt_submissions' => $cnt_submissions,
                                'groups' => $groups,
		]);
	}

  public function getEdit($id){
		$user = User::findOrFail($id);
		return view('user.edit_profile',['user' => $user]);
  }

  public function postId($id,Request $request){
    $this->validate($request, [
      'fullname' => 'max:255',
      'class'    => 'max:255',
      'description' => 'max:255',
    ]);
    $user = User::findOrFail($id);
    $profile_changed = false;

    if(Gate::allows('change_fullname' , $user)){
      $profile_changed = true;
      $user->fullname = $request->fullname;
    }

    if(Gate::allows('change_class' , $user)){
      $profile_changed = true;
      $user->class = $request->class;
    }

    if(Gate::allows('change_lock' , $user)){
      $profile_changed = true;
      $user->fullname_lock = $request->fullname_lock;
      $user->class_lock = $request->class_lock;
    }
    if(Gate::allows('change_description', $user)){
      $profile_changed = true;

      if(Auth::user()->has_role('admin')){
        $user->stored_description = $request->description;
      }

      $user->new_description = $request->description;
      $user->description_changed_at = DB::raw('now()');
    }

    if($profile_changed){
      $user->save();
    }

    return redirect('/users/'.$user->id);
  }

	public function putUsers(Request $request){
		$query = User::whereIn('id', $request->id);
		switch($request->action){
			case 'lock_fullname':
				$query->update(['fullname_lock'=> true]);
				break;
			case 'lock_class':
				$query->update(['class_lock'=> true]);
				break;
			case 'unlock_fullname':
				$query->update(['fullname_lock'=> false]);
				break;
			case 'unlock_class':
				$query->update(['class_lock'=> false]);
				break;
		}
		return back();
	}

	public function getUserFiles($id, Request $request){
		$user = User::findOrFail($id);

		$this->authorize('view_files', $user);

		$can_modify = Gate::allows('modify_files', $user);

		return FileManager::getRequests($request, [
			'disk' => 'files',
			'basepath' => strval($user->id),
			'title' => '<a href="/users/'.$user->id.'">'.$user->name.'</a>-'.trans('wzoj.files'),
			'modify' => $can_modify,
		]);
	}

	public function postUserFiles($id, Request $request){
		$user = User::findOrFail($id);

		$this->authorize('modify_files', $user);

		return FileManager::postRequests($request, [
			'disk' => 'files',
			'basepath' => strval($user->id),
		]);
	}
}
