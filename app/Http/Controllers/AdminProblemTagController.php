<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use App\ProblemTag;

class AdminProblemTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $tags = ProblemTag::all();
	    return view('admin.problem_tags',[
		'tags' => $tags,
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
	    $tag = ProblemTag::create();
	    return redirect('/admin/problem-tags#'.$tag->id);
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
	    ProblemTag::where('id', $id)
		    ->update([
			'name' => $request->name,
			'aliases' => $request->aliases,
			'reference_url' => $request->reference_url,
		    ]);
	    $tags = Cache::tags(['problem_tags'])->flush();

	    return redirect('/admin/problem-tags#'.$id);
    }

    static function resolveTags($tags, $parent_id, &$index){
	    foreach($tags as $tag){
		    ++$index;
		    ProblemTag::where('id', $tag->id)->update(['parent_id' => $parent_id, 'index' => $index]);
		    if(!empty($tag->children)){
			    self::resolveTags($tag->children, $tag->id, $index);
		    }
	    }
    }
    public function updateHierarchy(Request $request){
	    $tags = json_decode($request->tags);
	    $index = 0;
	    self::resolveTags($tags, 0, $index);
	    return back();
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
