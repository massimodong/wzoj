<?php

define('SL_PENDING' , 0);
define('SL_PENDING_REJUDGING' , 1);
define('SL_COMPILING' , 2);
define('SL_RUNNING' , 3);
define('SL_JUDGED' , 4);
define('SL_CANCELED' ,5);

define('CACHE_ONE_DAY', 1440);
define('CACHE_ONE_MONTH', 43200);

define('OJ_UDP_PORT', 13107);

function ojoption($name){
	return Cache::tags(['options'])->rememberForever($name, function() use ($name){
		return \App\Option::where('name', $name)->first()->value;
	});
}

function ojCanViewProblems($problemset){
	if(Gate::allows('update',$problemset)){
		return true;
	}
	return ($problemset->type === 'set') || (strtotime($problemset->contest_start_at)<time());
}

function ojcache($url){
	return $url."?v=".ojoption('current_version_id');
}

function ranklist_cmp_user($a, $b){
	if($a->score != $b->score) return $a->score < $b->score;
	else return $a->penalty > $b->penalty;
}

function ojShortTime($time){
	$cur = time();
	$dist = $cur - $time;
	if($dist < 60){
		return $dist.trans('wzoj.seconds').trans('wzoj.before');
	}else if($dist < 3600){
		$min = floor($dist/60);
		return $min.trans('wzoj.minutes').trans('wzoj.before');
	}else if($dist < 216000){
		$hour = floor($dist/3600);
		return $hour.trans('wzoj.hours').trans('wzoj.before');
	}else{
		$year = date('Y', $time);
		if($year == date('Y')){ //same year
			return date('m-d', $time);
		}else{
			return $year.trans('wzoj.year');
		}
	}
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function ojUdpSend($ip, $port, $msg){
	if($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
		socket_sendto($socket, $msg, strlen($msg), 0, $ip, $port);
	}
}
