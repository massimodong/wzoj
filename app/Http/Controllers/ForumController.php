<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ForumTopic;
use App\ForumReply;
use App\ForumTag;

use Gate;
use DB;

class ForumController extends Controller
{
	const PAGE_LIMIT = 4;
	public function getTopicsPublic($topics){
		$ret = [];
		foreach($topics as $topic){
			array_push($ret, [
				'id' => $topic->id,
				'title' => $topic->title,
				'preview' => $topic->preview,
				'user_id' => $topic->user_id,
				'user_name' => $topic->user->name,
				'updated_time' => ojShortTime(strtotime($topic->updated_at)),
				'updated_at' => $topic->updated_at,
				'cnt_views' => $topic->cnt_views,
			]);
		}
		return $ret;
	}
	public function getIndex(Request $request){
		$topics = ForumTopic::orderBy('updated_at', 'desc')->take(self::PAGE_LIMIT);
		if(isset($request->tags)){
			//todo:multi tags
			$topics = $topics->whereIn('id', function($query) use($request){
				$query->select('forum_topic_id')
				      ->from(with(new \App\ForumTag)->getTable())
				      ->where('value', '=', $request->tags[0]);
			});
		}
		$topics=$topics->get();
		$topics->load('replies', 'user');
		return view('forum.index',[
			'topics' => $this->getTopicsPublic($topics),
			'request' => $request,
		]);
	}

	public function getCreate(){
		return view('forum.create');
	}

	public function postIndex(Request $request){
		$preview = $request->content;
		if(!empty($preview)){
			$preview = \Html2Text\Html2Text::convert($preview);
			$preview = substr($preview,0 , 200);
		}
		$topic = $request->user()->topics()->create([
			'title' => $request->title,
			'preview' => $preview,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		]);
		$topic->reply($request->user(), $request->content);
		return redirect('/forum/'.$topic->id);
	}

	public function getTopic($id, Request $request){
		$topic = ForumTopic::findOrFail($id);
		$topic->load('replies.user', 'replies.topic', 'tags', 'user');

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

	public function putTopic($id, Request $request){
		$topic = ForumTopic::findOrFail($id);
		$this->authorize('update', $topic);
		$this->validate($request, [
			'title' => 'required',
		]);
		$topic->title = $request->title;
		$topic->save();
		return back();
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
		$reply->content = \Purifier::clean($request->content, 'forum');
		$reply->save();

		if($reply->index == 1){
			$preview = $reply->content;
			if(!empty($preview)){
				$preview = \Html2Text\Html2Text::convert($preview);
				$preview = substr($preview,0 , 200);
			}
			ForumTopic::where('id', $reply->forum_topic_id)
				->update(['preview' => $preview]);
		}

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

	public function postTag($id, Request $request){
		$topic = ForumTopic::findOrFail($id);
		$this->authorize('update', $topic);
		$this->validate($request, [
			'value' => 'required|min:1|max:10',
		]);
		$topic->tags()->create(["value" => $request->value]);
		return back();
	}

	public function deleteTag($id){
		$tag = ForumTag::findOrFail($id);
		$this->authorize('update', $tag->topic);
		$tag->delete();
		return back();
	}

	public function getAjaxTopics(Request $request){
		$this->validate($request, [
			"last_time" => "required",
		]);
		$topics = ForumTopic::where('updated_at', '<', $request->last_time)
			->orderBy('updated_at', 'desc')
			->take(self::PAGE_LIMIT);
		if(isset($request->tags)){
			//todo:multi tags
			$topics = $topics->whereIn('id', function($query) use($request){
				$query->select('forum_topic_id')
				      ->from(with(new \App\ForumTag)->getTable())
				      ->where('value', '=', $request->tags[0]);
			});
		}
		$topics = $topics->get();
		$topics->load('replies', 'user');
		return $this->getTopicsPublic($topics);
	}
}
