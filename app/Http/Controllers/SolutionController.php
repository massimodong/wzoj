<?php

namespace App\Http\Controllers;

use Event;

use Illuminate\Http\Request;

use App\Solution;
use App\Problemset;
use App\Problem;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Cache;
use Storage;
use DB;
use Auth;

use Illuminate\Support\Facades\Redis;

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

      $solutions = Solution::nohidden();
      // limits

      if(isset($request->problemset_id) && $request->problemset_id <> ''){
        if(!empty(\Request::get('contests'))){
          if(!in_array($request->problemset_id, \Request::get('contests'))) abort(403);
        }
        $problemset = Problemset::find($request->problemset_id);
        if($problemset){
          $solutions = $solutions->where('problemset_id', $problemset->id);
        }
      }else{
        if(!empty(\Request::get('contests'))){
          abort(403);
        }
      }

      if(isset($request->user_name) && $request->user_name <> ''){
        $user = \App\User::where('name', $request->user_name)->first();
        if($user){
          $solutions = $solutions->where('user_id', $user->id);
        }else{
          $solutions = $solutions->where('user_id', -1);
        }
      }

      if(isset($request->problem_id) && $request->problem_id <> ''){
        $problem = \App\Problem::find($request->problem_id);
        if($problem){
          $solutions = $solutions->where('problem_id', $problem->id);
        }
      }

      if(isset($request->score_min) && $request->score_min <> ''){
        $solutions = $solutions->where('score', '>=', $request->score_min);
      }

      if(isset($request->score_max) && $request->score_max <> ''){
        $solutions = $solutions->where('score', '<=', $request->score_max);
      }

      if(isset($request->language) && $request->language <> ''){
        $solutions = $solutions->where('language', $request->language);
      }
      if(isset($request->status) && $request->status <> ''){
        $solutions = $solutions->where('status', $request->status);
      }
      //limits end

      //get prev top
      $prev_id = -1;
      if($top <> $max_id){
        $tq = clone $solutions;
        $prev = $tq->where('id', '>=', $top)->orderBy('id', 'asc')->skip(self::PAGE_LIMIT)->first();
        if($prev){
          $prev_id = $prev->id;
        }else{
          $prev_id = $max_id;
        }
      }

      //get next top
      $next_id = -1;
      $tq = clone $solutions;
      $next = $tq->where('id', '<=', $top)->orderBy('id', 'desc')->skip(self::PAGE_LIMIT)->first();
      if($next){
        $next_id = $next->id;
      }

      $solutions = $solutions->where('id', '<=', $top)->public()->take(self::PAGE_LIMIT)->orderBy('id', 'desc')->get();

      return view('solutions.index',['solutions' => $solutions,
          'request' => $request,
          'prev_id' => $prev_id,
          'next_id' => $next_id,
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
     * Check if the solution is posted by a bot
     */
    private function bot_check($user){
        $cnt_minute_solutions = $user->solutions()
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 minute')))
            ->count();
        if($cnt_minute_solutions >= 20) $user->isbot(100);

        $cnt_hour_solutions = $user->solutions()
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->count();
        if($cnt_hour_solutions >= 60) $user->isbot(50);

        $cnt_day_solutions = $user->solutions()
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 day')))
            ->count();
        if($cnt_day_solutions >= 240) $user->isbot(25);
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

        if(!empty(\Request::get('contests'))){
            if(!in_array($problemset->id, \Request::get('contests'))){
                abort(403);
            }
        }

        $this->validate($request,[
        'problem_id' => 'required|exists:problem_problemset,problem_id,problemset_id,'.$problemset->id,
        'language' => 'required|in:0,1,2,4', //c,cpp,pas,java,python
        ]);

        if(!ojCanViewProblems($problemset)){
            return response()->json(['msg' => 'not allowed'], 422);
        }

        $solution_meta = $request->except(['_token', 'srcfile']);

        if($request->hasFile('srcfile') && $request->file('srcfile')->isValid()){
            $file = $request->file('srcfile');
            $solution_meta["code"] = file_get_contents($file->getRealPath());
        }

        $solution_meta["code_length"] = strlen($solution_meta["code"]);
        if($solution_meta["code_length"] <= 10){
            return response()->json(['msg' => trans('wzoj.code_too_short')], 422);
        }else if($solution_meta["code_length"] > 102400){ //100kb
            return response()->json(['msg' => trans('wzoj.code_too_long')], 422);
        }

        //we use redit to implement a `submit lock` for each user, which automatically expires in 3 seconds
        $res = Redis::set('wzoj.submit_lock.'.$request->user()->id, '1', 'ex', 3, 'nx');
        if($res == 'OK') $solution = $request->user()->solutions()->create($solution_meta);
        else return response()->json(['msg' => trans('wzoj.submit_too_frequent')], 422);

        $request->user()->answerfiles()
            ->where('problemset_id', $request->problemset_id)
            ->where('problem_id', $request->problem_id)
            ->update(['solution_id' => $solution->id,
                'user_id' => 0,
                'problemset_id' => 0,
                'problem_id' => 0]);

        $this->bot_check($request->user());

        wakeJudgers();

        return response()->json(['id' => $solution->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $solution = \App\Solution::findOrFail($id);

        if(!empty(\Request::get('contests'))){
            if(!in_array($solution->problemset_id, \Request::get('contests'))){
                abort(403);
            }
        }

        if(Auth::check()) $this->authorize('view', $solution);
        else return redirect('/auth/login');

        $testcases = $solution->testcaseByName();
        return view('solutions.show',['solution' => $solution,
                        'testcases' => $testcases,
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

    public function postSubmitAnswerfile(Request $request){
        $problemset = \App\Problemset::findOrFail($request->problemset_id);
        $this->authorize('view',$problemset);
        $this->validate($request,[
                'problem_id' => 'required|exists:problem_problemset,problem_id,problemset_id,'.$problemset->id,
                'answerfile' => 'required',
        ]);

        if(!ojCanViewProblems($problemset)){
            return back();
        }

        //check if is problem type 3
        $problem = $problemset->problems()->findOrFail($request->problem_id);
        if($problem->type <> 3){
            return response()->json(['error' => trans('wzoj.problem_not_type3')]);
        }

        $pinfo = pathinfo($request->file('answerfile')->getClientOriginalName());
        $filename = $pinfo['filename'];
        //check filename
        if($pinfo['extension'] <> 'out'){
            return response()->json(['error' => trans('wzoj.not_out_file')]);
        }
        if(!Storage::disk('data')->has('/'.$problem->id.'/'.$filename.'.in')){
            return response()->json(['error' => trans('wzoj.invalid_file')]);
        }

        $answerfile = $request->user()->answerfiles()
            ->where('problemset_id', $request->problemset_id)
            ->where('problem_id', $request->problem_id)
            ->where('filename', $filename)
            ->first();
        if($answerfile == NULL){
            $answerfile = $request->user()->answerfiles()->create([
                    'problemset_id' => $request->problemset_id,
                    'problem_id' => $request->problem_id,
                    'filename' => $filename,
            ]);
        }

        $answerfile->answer = file_get_contents($request->file('answerfile')->getRealPath());
        $answerfile->save();

        $ret=[];
        return response()->json($ret);
    }
}
