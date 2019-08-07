<h1 class="page-header text-center">{{$problem->name}}</h1>
@if (Auth::check() && Auth::user()->has_role('admin'))
  <div class="pull-right"><a href="/admin/problems/{{$problem->id}}">{{trans('wzoj.edit')}}</a></div>
@endif

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

@if (strlen($problem->description))
<h3>{{trans('wzoj.problem_description')}}:</h3>
{!! $problem->description !!}
@endif

@if (strlen($problem->inputformat))
<h3>{{trans('wzoj.input_format')}}:</h3>
{!! $problem->inputformat !!}
@endif

@if (strlen($problem->outputformat))
<h3>{{trans('wzoj.output_format')}}:</h3>
{!! $problem->outputformat !!}
@endif

@if (strlen($problem->sampleinput))
<h3>{{trans('wzoj.sample_input')}}:</h3>
<pre>
{{$problem->sampleinput}}
</pre>
@endif

@if (strlen($problem->sampleoutput))
<h3>{{trans('wzoj.sample_output')}}:</h3>
<pre>
{{$problem->sampleoutput}}
</pre>
@endif

@if (isset($download_url))
<p><a href='{{$download_url}}'>{{trans('wzoj.download_attached_file')}}</a></p>
@endif

@if (strlen($problem->hint))
<h3>{{trans('wzoj.hints')}}:</h3>
{!! $problem->hint !!}
@endif

{{trans('wzoj.time_limit')}}:{{$problem->timelimit}}ms<br>
{{trans('wzoj.memory_limit')}}:{{$problem->memorylimit}}MB<br>

<hr>
@if (strlen($problem->source))
<p>{{trans('wzoj.source')}}:{{$problem->source}}</p>
@endif
