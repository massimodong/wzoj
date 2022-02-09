<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLog;

class AdminUserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->flash();
      $logs = UserLog::with(['user'])->orderBy('id', 'desc');

      if(isset($request->levels)){
        $logs = $logs->whereIn('level', $request->levels);
      }

      if(isset($request->uids)){
        $logs = $logs->whereIn('user_id', $request->uids);
      }

      if(isset($request->request_ip) && $request->request_ip != ''){
        $logs = $logs->where('request_ip', $request->request_ip);
      }

      if(isset($request->actions)){
        $logs = $logs->whereIn('action_name', $request->actions);
      }

      $logs = $logs->cursorPaginate(15)->withQueryString();
      return view('admin.user_logs', [
        'logs' => $logs,
      ]);
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
        //
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
