<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ForumTopic;
use App\ForumReply;

use Gate;
use DB;

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

	public function deleteTopic($id){
		$topic = ForumTopic::findOrFail($id);
		$this->authorize('delete', $topic);
		$topic->delete();
		return redirect('/forum');
	}

	public function postReply($id, Request $request){
		$topic = ForumTopic::findOrFail($id);
		$topic->reply($request->user(), $request->content);
		ForumTopic::where('id', $topic->id)
			->update(['updated_at' => DB::raw('NOW()')]);
		return redirect('/forum/'.$topic->id.'#content');
	}
	public function putReply($id, Request $request){
		$reply = ForumReply::findOrFail($id);
		$this->authorize('update', $reply);
		$reply->content = $request->content;
		$reply->save();

		ForumTopic::where('id', $reply->forum_topic_id)
			->update(['updated_at' => DB::raw('NOW()')]);
		return redirect('/forum/'.$reply->forum_topic_id.'#reply-'.$reply->id);
	}

	public function deleteReply($id){
		$reply = ForumReply::findOrFail($id);
		$this->authorize('delete', $reply);
		if($reply->index == 1){
			$reply->topic->delete();
		}else{
			$reply->delete();
		}
	}
}
