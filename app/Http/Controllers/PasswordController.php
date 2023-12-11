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
      if(isset($request->new_password) && $request->new_password != ''){
        Auth::user()->password = bcrypt($request->new_password);
        Auth::user()->is_pwd_outdate = false;
      }
      Auth::user()->name = $request->name;
      Auth::user()->email = $request->email;
      Auth::user()->save();
      return redirect('/users/'.Auth::user()->id);
    }else{
      return back()
        ->withErrors(['old_password' => trans('wzoj.password_incorrect')]);
    }
  }
}
