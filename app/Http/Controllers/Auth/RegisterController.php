<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|username|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'fullname' => 'max:255',
            'class' => 'max:255',
            'token' => 'required|invitation',
            'password' => 'required|min:6|confirmed',
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
          $newuser->fullname=$data['fullname'];
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
}
