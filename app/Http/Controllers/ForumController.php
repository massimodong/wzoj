<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ForumTopic;

class ForumController extends Controller
{
	public function getIndex(){
		$topics = ForumTopic::orderBy('updated_at', 'desc')->take(10)->get();
		return view('forum.index',[
			'topics' => $topics,
		]);
	}

	public function getCreate(){
		return view('forum.create');
	}

	public function postIndex(Request $request){
		$topic = $request->user()->topics()->create([
			'title' => $request->title,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);
		$topic->reply($request->user(), $request->content);
		return redirect('/forum/'.$topic->id);
	}

	public function getTopic($id, Request $request){
		$topic = ForumTopic::findOrFail($id);

		//add views
		if(!in_array($topic->id , session('topics_read',[])) ){
                        $topic->cnt_views++;
                        $topic->save();
                        $request->session()->push('topics_read',$topic->id);
                }

		$replies = $topic->replies;

		return view('forum.topic',[
			'topic' => $topic,
			'replies' => $replies,
		]);
	}
}
