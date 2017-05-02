<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
    protected $redirectPath = '/';
    protected $username = 'name';

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|min:3|max:31|unique:users',
            'email' => 'required|email|max:255|unique:users',
	    'fullname' => 'max:255',
	    'class' => 'max:255',
	    'token' => 'required|invitation',
            'password' => 'required|confirmed|min:6',
	    'captcha' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {   
	$invitation = \App\Invitation::where('token',$data['token'])->firstOrFail();
	if($invitation->remaining == 0){
		abort(503);
	}else if($invitation->remaining > 0){
		$invitation->remaining--;
		$invitation->save();
	}
        $newuser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
	if($invitation->fullname <> ''){
		$newuser->fullname=$invitation->fullname;
		$newuser->fullname_lock = true;
	}else{
		$newuser['fullname']=$data['fullname'];
	}

	if($invitation->class <> ''){
		$newuser->class=$invitation->class;
		$newuser->class_lock = true;
	}else{
		$newuser->class=$data['class'];
	}

	$newuser->save();

	foreach($invitation->groups as $group){
		$newuser->groups()->attach($group->id);
	}

	return $newuser;
    }

    public function getLogin(Request $request){
	    \Session::put('url.intended', \URL::previous());
	    return view('auth.login');
    }

    private function authenticated(Request $request, $user){
	    //roles
	    $roles = [];
	    foreach($user->roles as $role){
		    $roles[$role->name] = true;
	    }
	    $request->session()->put('roles', $roles);

	    //problemsets
	    $problemsets_id = [];
	    $problemsets = [];
	    $groups = $user->groups()->with('problemsets')->get();
	    foreach($groups as $group){
		    foreach($group->problemsets as $problemset){
			    if(!isset($problemsets_id[$problemset->id])){
				    $problemsets_id[$problemset->id] = true;
				    array_push($problemsets, $problemset);
			    }
		    }
	    }
	    $public_problemsets = \App\Problemset::where('public', true)->get();
	    foreach($public_problemsets as $problemset){
		    if(!isset($problemsets_id[$problemset->id])){
			    $problemsets_id[$problemset->id] = true;
			    array_push($problemsets, $problemset);
		    }
	    }
	    $request->session()->put('problemsets', $problemsets);

	    return redirect()->intended($this->redirectPath());
    }

    public function oj_getRegister(Request $request){
	    if(isset($request->token)){
		    $invitation = \App\Invitation::where('token',$request->token)
			    ->where('remaining' , '<>' , 0)
			    ->first();
		    if($invitation == NULL) return redirect('/auth/register')
			    ->withErrors(trans('wzoj.invalid_token'))
			    ->withInput();
		    return view('auth.register',['invitation'=>$invitation]);
	    }else{
		    $invitations = \App\Invitation::where('private' , false)
			    ->where('remaining' , '<>' , 0)
			    ->get();
		    return view('auth.choose_register_token',['invitations'=>$invitations]);
	    }
    }
}
