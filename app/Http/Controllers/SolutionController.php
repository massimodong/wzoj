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
    const PAGE_LIMIT = 20;
    public function index(Request $request)
    {
	    $this->validate($request, [
		    'top' => 'integer',
	    ]);
	    $max_id = Solution::max('id');
	    if(isset($request->top)){
		    $top = $request->top;
	    }else{
		    $top = $max_id;
	    }

	    $solutions = Solution::where('id', '<>', 0);
	    //todo limits

	    //get prev top
	    $prev_url = '';
	    if($top <> $max_id){
		$tq = clone $solutions;
	    	$prev = $tq->where('id', '>=', $top)->orderBy('id', 'asc')->skip(self::PAGE_LIMIT)->first();
		if($prev){
			$prev_top = $prev->id;
		}else{
			$prev_top = $max_id;
		}
		$prev_url = '/solutions?top='.$prev_top;
	    }
	    //echo "prev_url:".$prev_url."<br>";

	    //get next top
	    $next_url = '';
	    $tq = clone $solutions;
	    $next = $tq->where('id', '<=', $top)->orderBy('id', 'desc')->skip(self::PAGE_LIMIT)->first();
	    if($next){
		    $next_url = '/solutions?top='.$next->id;
	    }
	    //echo "next_url:".$next_url."<br>";

	    $solutions = $solutions->where('id', '<=', $top)->take(self::PAGE_LIMIT)->orderBy('id', 'desc')->get();
	    return view('solutions.index',['solutions' => $solutions,
	    				'prev_url' => $prev_url,
	    				'next_url' => $next_url,
	    				'last_solution_id' => $top]);
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

	    $solution->code_length = strlen($solution->code);
	    $solution->save();

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
	    //$this->authorize('view',$solution);
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
