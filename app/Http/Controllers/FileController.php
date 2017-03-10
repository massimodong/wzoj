<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Auth;

use App\File;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function __construct(){   
	    $this->middleware('auth', ['except' => 'showfile']);
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    foreach(Auth::user()->files as $file){
		    echo $file->name."<br>";
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
			    'file' => 'required',
	    ]);

	    $filePath=$request->file('file')->getRealPath();
	    $extension = $request->file('file')->guessExtension();

	    $name=((string)time()).((string)rand(0,1000000000)).'.'.$extension;

	    $file = $request->user()->files()->create([
			    'name' => $name,
	    ]);

	    Storage::disk('files')->put(
			    $file->getPath(),
			    file_get_contents($filePath)
			    );
	    return response()->json(['location' => $file->getUrl()]);
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

    public function showfile($user_id, $name){
	    $path = '/'.$user_id.'/'.$name;

	    $finfo = finfo_open(FILEINFO_MIME_TYPE);
	    $mime = finfo_file($finfo,  storage_path('app').'/files'.$path);
	    finfo_close($finfo);

	    return response(Storage::disk('files')->get($path), 200)->header('Content-Type', $mime);
    }
}
