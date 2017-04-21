<?php

define('SL_PENDING' , 0);
define('SL_PENDING_REJUDGING' , 1);
define('SL_COMPILING' , 2);
define('SL_RUNNING' , 3);
define('SL_JUDGED' , 4);
define('SL_CANCELED' ,5);

define('CACHE_ONE_DAY', 1440);

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
