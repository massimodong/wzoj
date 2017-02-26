<?php

define('SL_PENDING' , 0);
define('SL_PENDING_REJUDGING' , 1);
define('SL_COMPILING' , 2);
define('SL_RUNNING' , 3);
define('SL_JUDGED' , 4);
define('SL_CANCELED' ,5);

function ojoption($name){
	static $ojoptions = [];
	if(isset($ojoptions[$name])) return $ojoptions[$name];
	else return $ojoptions[$name] = \App\Option::where('name',$name)->first()->value;
}

function ojCanViewProblems($problemset){
	if(Gate::allows('update',$problemset)){
		return true;
	}
	return ($problemset->type === 'set') || (strtotime($problemset->contest_start_at)<time());
}
