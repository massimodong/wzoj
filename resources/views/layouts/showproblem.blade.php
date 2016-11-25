<div>
<h3>{{$problem->name}}</h3>

<small>
@if ($problem->type == 1)
	std
@elseif ($problem->type == 2)
	inter
@elseif ($problem->type == 3)
	answer
@endif

@if ($problem->spj)
	spj
@endif

{{$problem->timelimit}}ms

{{$problem->memorylimit}}mb
</small>


<h4>description:</h4>
{{$problem->description}}

<h4>input:</h4>
{{$problem->inputformat}}

<h4>output:</h4>
{{$problem->outputformat}}

<h4>sample in:</h4>
{{$problem->sampleinput}}

<h4>sample out:</h4>
{{$problem->sampleoutput}}

<h4>hint:</h4>
{{$problem->hint}}

<hr>
<p>from:{{$problem->source}}</p>

</div>
