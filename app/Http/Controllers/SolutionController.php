<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Solution;
use App\Problemset;
use App\Problem;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
	    $problemset = Problemset::findOrFail($request->problemset_id);
	    $this->authorize('view',$problemset);
	    $this->validate($request,[
		'problem_id' => 'required|exists:problem_problemset,problem_id,problemset_id,'.$problemset->id,
		'language' => 'required|in:0,1,2', //c,cpp,pas
	    ]);

	    if(!ojCanViewProblems($problemset)){
		    return back();
	    }

	    $solution = $request->user()->solutions()->create($request->all());

	    return redirect('/solutions/'.$solution->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $solution = Solution::findOrFail($id);
	    $this->authorize('view',$solution);
	    return view('solutions.show',['solution'=>$solution]);
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
