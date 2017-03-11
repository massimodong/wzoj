<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Auth;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	$this->middleware('guest', ['except' => ['getChangePassword', 'postChangePassword']]);
    }

    public function getChangePassword(){
	    if(!Auth::check()) return redirect('/');
	    return view('auth.change_password');
    }

    public function postChangePassword(Request $request){
	    if(!Auth::check()) return redirect('/');
	    $this->validate($request, [
		'name' => 'required|min:3|max:255|unique:users,name,'.Auth::user()->id,
		'email' => 'required|email|max:255|unique:users,email,'.Auth::user()->id,
		'new_password' => 'confirmed|min:6',
		'old_password' => 'required',
	    ]);
	    if(Auth::attempt(['name' => Auth::user()->name, 'password' => $request->old_password])){
		    if(isset($request->new_password) && $request->new_password != '')
			    Auth::user()->password = bcrypt($request->new_password);
		    Auth::user()->name = $request->name;
		    Auth::user()->email = $request->email;
		    Auth::user()->save();
		    return redirect('/users/'.Auth::user()->id);
	    }else{
		    return back()
			    ->withErrors(['old_password' => trans('wzoj.password_incorrect')]);
	    }
    }

    /**
     * Use Captcha
     */
    public function postEmailWithCaptcha(Request $request){
            $this->validate($request,[
                'captcha'=>'required|captcha',
            ]);
            return PasswordController::postEmail($request);
    }
}
