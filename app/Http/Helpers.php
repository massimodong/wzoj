<?php

function ojoption($name){
	return \App\Option::where('name',$name)->first()->value;
}
