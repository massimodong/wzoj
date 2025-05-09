<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\User;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if(isset($request->uid)){
        $user = User::findOrFail($request->uid);
        return view('admin.users', [
          'search_user' => $user,
        ]);
      }else{
        return view('admin.users');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|integer|exists:users',
            'name' => 'required',
            'phone' => 'digits:11',
            'bt' => 'integer',
        ]);

        $user = User::findOrFail($request->id);
        if($user->name != $request->name) 
            return back()->withErrors(trans('wzoj.msg_users_update_id_name_match'));

        if($user->has_role('admin')) abort(403);
        if($user->has_role('manager') && !Auth::user()->has_role('admin')) abort(403);

        if(isset($request->phone) && $request->phone != ''){
            $user->phone_number = $request->phone;
            logAction('admin_change_user_phone', ["user_id" => $user->id, "phone" => $request->phone], LOG_SEVERE);
        }

        if(isset($request->new_password) && $request->new_password != ''){
            $user->password = bcrypt($request->new_password);
            $user->is_pwd_outdate = true;
            logAction('admin_change_user_password', ["user_id" => $user->id, "password" => $request->new_password], LOG_SEVERE);
        }

        if(isset($request->bt) && $request->bt != ''){
            $user->bot_tendency = $request->bt;
            logAction('admin_change_user_bt', ["user_id" => $user->id, "bt" => $request->bt], LOG_SEVERE);
        }

        $user->save();

        return back()->with(['status' => trans('wzoj.success')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
