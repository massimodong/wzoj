<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

class AdminAccountsGenerateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('admin.accounts_generate');
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
      $this->validate($request, [
          'prefix' => 'required|min:3|max:10',
          'startno' => 'required|integer',
          'password_length' => 'required|integer',
          'class' => 'max:255',
          'fullname' => 'required',
          'groups_id[]' => 'array',
      ]);

      $fullnames = explode("\n", trim($request->fullname));
      $name_cnt = $request->startno;

      download_send_headers('accounts-' . date("Y-m-d") . ".csv");
      $df = fopen("php://output", "w");
      utf8_bom($df);
      $head = array(trans('wzoj.fullname'), trans('wzoj.account'), trans('wzoj.password'));
      fputcsv($df, $head);

      foreach($fullnames as $fullname){
        do{
          $name = $request->prefix.($name_cnt++);
        }while(User::where('name', $name)->count());

        $password = str_random($request->password_length);
        $newuser = User::create([
            'name' => $name,
            'class' => $request->class,
            'class_lock' => true,
            'fullname' => $fullname,
            'fullname_lock' => true,
            'password' => bcrypt($password),
        ]);

        if(isset($request->groups_id) && count($request->groups_id)){
          foreach($request->groups_id as $gid){
            $newuser->groups()->attach($gid);
          }
        }

        $item = array($newuser->fullname, $name, $password);
        fputcsv($df, $item);
      }

      fclose($df);
      return;
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
