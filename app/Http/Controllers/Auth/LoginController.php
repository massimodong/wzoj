<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }

    public function username()
    {
      return 'name';
    }

    public function authenticated($request, $user){
      $roles = [];
      foreach($user->roles as $role){
        $roles[$role->name] = true;
      }
      $request->session()->put('roles', $roles);
      $user->last_login_at = date('Y-m-d H:i:s');
      $user->last_token = Session::getId();
      $user->save();
    }

    public function getLogout(){
      return view('auth.logout');
    }

    public function logout(){
      Auth::logout();
      \Session::forget('roles');
      \Session::forget('problemsets_last_updated_at');
      \Session::forget('problemsets');
      return redirect("/");
    }
}
