<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Auth;

class PasswordController extends Controller
{
  public function getChangePassword(){
    if(!Auth::check()) return redirect('/');
    return view('auth.change_password');
  }

  public function postChangePassword(Request $request){
    if(!Auth::check()) return redirect('/');
    $this->validate($request, [
        'name' => 'required|username|unique:users,name,'.Auth::user()->id,
        'email' => 'required|email|max:255|unique:users,email,'.Auth::user()->id,
        'new_password' => ['confirmed', Password::defaults()],
        'old_password' => 'required',
    ]);
    if(Auth::attempt(['name' => Auth::user()->name, 'password' => $request->old_password])){
      logAction('change_password', [
        'old_name' => Auth::user()->name,
        'new_name' => $request->name,
        'old_email' => Auth::user()->email,
        'new_email' => $request->email,
        'phone' => Auth::user()->phone_number,
        'password_changed' => (isset($request->new_password) && $request->new_password != ''),
      ], LOG_SEVERE);

      if(isset($request->new_password) && $request->new_password != ''){
        Auth::user()->password = bcrypt($request->new_password);
        Auth::user()->is_pwd_outdate = false;
        Auth::user()->bot_tendency = 0;
      }
      Auth::user()->name = $request->name;
      Auth::user()->email = $request->email;
      Auth::user()->save();
      return back();
      //return redirect('/users/'.Auth::user()->id);
    }else{
      return back()
        ->withErrors(['old_password' => trans('wzoj.password_incorrect')]);
    }
  }

  public function postLinkPhone(Request $request){
    $this->validate($request, [
        'verification_code' => ['required', new \App\Rules\VerificationCode(Auth::user(), 'link-phone', $request)],
    ]);

    Auth::user()->phone_number = $request->phone;
    Auth::user()->save();

    logAction('link_phone', [
      "phone_number" => $request->phone,
    ], LOG_SEVERE);

    return back();
  }
}
