<?php

function ojoption($name){
	return \App\Option::where('name',$name)->first()->value;
}

function ojCanViewProblems($problemset){
	if(Gate::allows('update',$problemset)){
		return true;
	}
	return ($problemset->type === 'set') || (strtotime($problemset->contest_start_at)<time());
}
