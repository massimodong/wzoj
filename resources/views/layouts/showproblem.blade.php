<div class="col-xs-12">
<h1 class="page-header text-center">{{$problem->name}}</h1>

<small>
@if ($problem->type == 2)
{{trans('wzoj.interactive')}}
@elseif ($problem->type == 3)
{{trans('wzoj.submit_answer')}}
@endif

@if ($problem->spj)
{{trans('wzoj.spj')}}
@endif

</small>

<h3>{{trans('wzoj.problem_description')}}:</h3>
{!! Purifier::clean($problem->description) !!}

<h3>{{trans('wzoj.input_format')}}:</h3>
{!! Purifier::clean($problem->inputformat) !!}

<h3>{{trans('wzoj.output_format')}}:</h3>
{!! Purifier::clean($problem->outputformat) !!}

<h3>{{trans('wzoj.sample_input')}}:</h3>
<pre>
{{$problem->sampleinput}}
</pre>

<h3>{{trans('wzoj.sample_output')}}:</h3>
<pre>
{{$problem->sampleoutput}}
</pre>

<h3>{{trans('wzoj.hints')}}:</h3>
{!! Purifier::clean($problem->hint) !!}

{{trans('wzoj.time_limit')}}:{{$problem->timelimit}}s<br>
{{trans('wzoj.memory_limit')}}:{{$problem->memorylimit}}MB<br>

<hr>
<p>{{trans('wzoj.source')}}:{{$problem->source}}</p>

</div>
