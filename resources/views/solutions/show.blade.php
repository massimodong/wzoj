@extends ('layouts.master')

@section ('title')
solution
@endsection

@section ('content')

<h3>problem:{{$solution->problem->name}}</h3>

@foreach ($solution->testcases as $testcase)
	<p>
	<hr>
	filename - {{$testcase->filename}}<br>
	time - {{$testcase->time_used}} ms<br>
	memory - {{$testcase->memory_used}} Mb<br>
	verdict - {{$testcase->verdict}}<br>
	score - {{$testcase->score}}<br>
	checklog - {{$testcase->checklog}}<br>
	<hr>
	</p>
@endforeach

@endsection
