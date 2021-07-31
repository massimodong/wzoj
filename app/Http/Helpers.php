<?php

define('SL_PENDING' , 0);
define('SL_PENDING_REJUDGING' , 1);
define('SL_COMPILING' , 2);
define('SL_RUNNING' , 3);
define('SL_JUDGED' , 4);
define('SL_CANCELED' ,5);

define('CACHE_ONE_MINUTE', 60);
define('CACHE_ONE_DAY', 86400);
define('CACHE_ONE_MONTH', 2592000);

define('OJ_UDP_PORT', 13107);

function ojoption($name){
	return Cache::tags(['options'])->rememberForever($name, function() use ($name){
		return \App\Option::where('name', $name)->first()->value;
	});
}

function ojCanViewProblems($problemset, $vp = NULL){
	if(Gate::allows('update',$problemset)){
		return true;
	}
  if($problemset->type == 'set') return true;

  switch($problemset->participate_type){
    case 0:
      return strtotime($problemset->contest_start_at) < time();
    case 1:
      if((!isset($vp)) && Auth::check()){
        $vp = Auth::user()->virtual_participations()
                          ->where('problemset_id', $problemset->id)
                          ->orderBy('id', 'desc')
                          ->first();
        if(!isset($vp)) $vp = false;
      }

      if(strtotime($problemset->contest_end_at) < time()) return true;
      if($vp && $vp->contest_start_at < time()) return true;
      return false;
    case 2:
      return false;
  }
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

function utf8_bom($df){
  fwrite($df, chr(239) . chr(187) . chr(191));
}

function ojUdpSend($ip, $port, $msg){
	if($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
		socket_sendto($socket, $msg, strlen($msg), 0, $ip, $port);
	}
}

function wakeJudgers(){
	foreach(\App\Judger::where('ip_addr', '<>', '')->get() as $judger){
		ojUdpSend($judger->ip_addr, OJ_UDP_PORT, $judger->token);
	}
}

function max_scores($user_ids, $problemset_ids, $problem_ids){
	$result = [];
	$uncached_user_ids = [];
	$uncached_problemset_ids = [];
	$uncached_problem_ids = [];
	$miss = false;

	$cnt=0;
	foreach($user_ids as $uid){
		$result[$uid] = [];
		foreach($problemset_ids as $psid){
			$result[$uid][$psid] = [];
			foreach($problem_ids as $pid){
				$path = $uid.'-'.$psid.'-'.$pid;
				if(Cache::tags(['problemsets', 'max_score'])->has($path)){
					$result[$uid][$psid][$pid] = Cache::tags(['problemsets', 'max_score'])->get($path);
				}else{
					$result[$uid][$psid][$pid] = -1;
					array_push($uncached_user_ids, $uid);
					array_push($uncached_problemset_ids, $psid);
					array_push($uncached_problem_ids, $pid);
					$miss = true;
				}
				++$cnt;
			}
		}
	}
	$uncached_user_ids = array_unique($uncached_user_ids);
	$uncached_problemset_ids = array_unique($uncached_problemset_ids);
	$uncached_problem_ids = array_unique($uncached_problem_ids);

	if(!$miss) return $result;

	$solutions = \App\Solution::whereIn('user_id', $uncached_user_ids)
		->whereIn('problemset_id', $uncached_problemset_ids)
		->whereIn('problem_id', $uncached_problem_ids)
		->select(['user_id', 'problemset_id', 'problem_id', 'score'])
		->get();
	foreach($solutions as $solution){
		$uid = $solution->user_id;
		$psid = $solution->problemset_id;
		$pid = $solution->problem_id;

		$path = $uid.'-'.$psid.'-'.$pid;
		if($solution->score > $result[$uid][$psid][$pid]){
			$result[$uid][$psid][$pid] = $solution->score;
		}
	}

	foreach($uncached_user_ids as $uid){
		foreach($uncached_problemset_ids as $psid){
			foreach($uncached_problem_ids as $pid){
				$path = $uid.'-'.$psid.'-'.$pid;
				Cache::tags(['problemsets', 'max_score'])->put($path, $result[$uid][$psid][$pid], CACHE_ONE_DAY);
			}
		}
	}
	return $result;
}

function array_by_id($col){
  $res = [];
  foreach($col as $item) $res[$item->id] = $item;
  return $res;
}

function ojPrimusClass($language){
  switch($language){
    case 0: return "language-c";
    case 1: return "language-cpp";
    case 2: return "language-pascal";
    case 3: return "language-java";
    case 4: return "language-python";
  }
}

function time2String($t){
  $y = (int)gmdate('Y', $t) - 1970;
  $d = (int)gmdate('z', $t);
  $h = (int)gmdate('G', $t);
  $i = (int)gmdate('i', $t);
  $s = (int)gmdate('s', $t);
  $ret = "";
  if($y) $ret .= $y.trans('wzoj.years');
  if($d) $ret .= $d.trans('wzoj.days');
  if($h) $ret .= $h.trans('wzoj.hours');
  if($i) $ret .= $i.trans('wzoj.minutes');
  if($s) $ret .= $s.trans('wzoj.seconds');
  return $ret;
}
