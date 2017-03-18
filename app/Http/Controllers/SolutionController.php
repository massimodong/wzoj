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
    public function __construct()
    {   
	    $this->middleware('auth', ['except' => ['index', 'show']]);
    }  

    const PAGE_LIMIT = 20;
    public function index(Request $request)
    {
	    $this->validate($request, [
		    'top' => 'integer',
		    'problemset_id' => 'integer|exists:problemsets,id',
		    'user_name' => 'exists:users,name',
		    'problem_id' => 'integer|exists:problems,id',
		    'score_min' => 'integer|min:0|max:100',
		    'score_max' => 'integer|min:0|max:100',
		    'language' => 'integer|in:0,1,2,4',
	    ]);
	    $max_id = Solution::max('id');
	    if(isset($request->top)){
		    $top = $request->top;
	    }else{
		    $top = $max_id;
	    }

	    $solutions = Solution::where('id', '<>', 0);
	    // limits
	    //todo: abandon url_limits
	    $url_limits = '';
	    $problemset = NULL;
	    if(isset($request->problemset_id) && $request->problemset_id <> ''){
		    $problemset = Problemset::find($request->problemset_id);
		    if($problemset){
			    $solutions = $solutions->where('problemset_id', $problemset->id);
			    $url_limits.='&problemset_id='.$problemset->id;
		    }
	    }

	    if(isset($request->user_name) && $request->user_name <> ''){
		    $user = \App\User::where('name', $request->user_name)->first();
		    if($user){
			    $solutions = $solutions->where('user_id', $user->id);
		    }else{
			    $solutions = $solutions->where('user_id', -1);
		    }
		    $url_limits.='&user_name='.$request->user_name;
	    }

	    if(isset($request->problem_id) && $request->problem_id <> ''){
		    $problem = \App\Problem::find($request->problem_id);
		    if($problem){
			    $solutions = $solutions->where('problem_id', $problem->id);
			    $url_limits.='&problem_id='.$problem->id;
		    }
	    }

	    if(isset($request->score_min) && $request->score_min <> ''){
		    $solutions = $solutions->where('score', '>=', $request->score_min);
		    $url_limits.='&score_min='.$request->score_min;
	    }

	    if(isset($request->score_max) && $request->score_max <> ''){
		    $solutions = $solutions->where('score', '<=', $request->score_max);
		    $url_limits.='&score_max='.$request->score_max;
	    }

	    if(isset($request->language) && $request->language <> ''){
		    $solutions = $solutions->where('language', $request->language);
		    $url_limits.='&language='.$request->language;
	    }
	    //limits end

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

	    $solutions = $solutions->where('id', '<=', $top)->public()->take(self::PAGE_LIMIT)->orderBy('id', 'desc')->get();

	    $solutions_judging = Solution::where('status', SL_COMPILING)->orWhere('status', SL_RUNNING)->get(['id']);
	    return view('solutions.index',['solutions' => $solutions,
			    		'request' => $request,
	    				'prev_url' => $prev_url,
	    				'next_url' => $next_url,
					'url_limits' => $url_limits,
	    				'last_solution_id' => isset($request->top)?-1:$top,
	    				'problemset' => $problemset,
	    				'solutions_judging' => $solutions_judging]);
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
		'language' => 'required|in:0,1,2,4', //c,cpp,pas,java,python
	    ]);

	    if(!ojCanViewProblems($problemset)){
		    return back();
	    }

	    $solution_meta = $request->except(['_token', 'srcfile']);

	    if($request->hasFile('srcfile') && $request->file('srcfile')->isValid()){
		    $file = $request->file('srcfile');
		    $solution_meta["code"] = file_get_contents($file->getRealPath());
	    }

	    if(strlen($solution_meta["code"]) <= 10){
		    return back()
			    ->withErrors(trans('wzoj.code_too_short'))
			    ->withInput();
	    }

	    $solution = $request->user()->solutions()->create($solution_meta);

	    $solution->code_length = strlen($solution->code);
	    $solution->save();

	    $request->user()->answerfiles()
		    ->where('problemset_id', $request->problemset_id)
		    ->where('problem_id', $request->problem_id)
		    ->update(['solution_id' => $solution->id,
				'user_id' => 0,
		    		'problemset_id' => 0,
		    		'problem_id' => 0]);

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
	    return view('solutions.show',['solution' => $solution,
	    				'problemset' => $solution->problemset]);
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
