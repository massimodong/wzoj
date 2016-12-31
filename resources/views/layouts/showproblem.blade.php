<div class="col-lg-12">
<h1>{{$problem->name}}</h1>

<small>
@if ($problem->type == 2)
	interactive
@elseif ($problem->type == 3)
	submit answer
@endif

@if ($problem->spj)
	spj
@endif

</small>

<hr>

{!! Purifier::clean($problem->description) !!}

<h3>input:</h3>
{!! Purifier::clean($problem->inputformat) !!}

<h3>output:</h3>
{!! Purifier::clean($problem->outputformat) !!}

<h3>samplein:</h3>
<pre>
{{$problem->sampleinput}}
</pre>

<h3>sampleout:</h3>
<pre>
{{$problem->sampleoutput}}
</pre>

<h3>hint:</h3>
{!! Purifier::clean($problem->hint) !!}

<h3>Limits</h3>
timelimit:{{$problem->timelimit}}s<br>
memorylimit:{{$problem->memorylimit}}MB<br>

<hr>
<p>from:{{$problem->source}}</p>

</div>
