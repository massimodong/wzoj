<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumTopicAddPreview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_topics', function (Blueprint $table) {
		$table->longText('preview')->after('title');
        });
	foreach(\App\ForumTopic::with('replies')->get() as $topic){
		$topic->preview = $topic->replies[0]->content;
		if(!empty($topic->preview)){
			$topic->preview = \Html2Text\Html2Text::convert($topic->preview);
			$topic->preview = substr($topic->preview,0 , 200);
		}
		$topic->save();
	}
	foreach(\App\ForumReply::all() as $reply){
		$reply->content = Purifier::clean($reply->content, 'forum');
		$reply->save();
	}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_topics', function (Blueprint $table) {
		$table->dropColumn('preview');
        });
    }
}
