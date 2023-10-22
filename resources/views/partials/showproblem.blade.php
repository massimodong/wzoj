<div style="font-size: 20px">
<h1 class="page-header text-center">{{$problem->name}}</h1>

<div class="w-100 text-center">
<small>
@if ($problem->type == 2)
{{trans('wzoj.interactive')}}
@elseif ($problem->type == 3)
{{trans('wzoj.submit_answer')}}
@endif

@if ($problem->spj)
{{trans('wzoj.spj')}}
@endif

@if (isset($cnt_submit) && $cnt_submit > 0)
  {{trans('wzoj.count_submit')}}: {{$cnt_submit}}, {{trans('wzoj.pass_rate')}}: {{round(100 * $cnt_ac / $cnt_submit, 2)}}%, {{trans('wzoj.avrg_score')}}: {{round($tot_score / $cnt_submit, 2)}}
@endif
</small>
</div>

@if (strlen($problem->description))
<h4><b>{{trans('wzoj.problem_description')}}:</b></h4>
{!! $problem->description !!}
@endif

@if (strlen($problem->inputformat))
<h4><b>{{trans('wzoj.input_format')}}:</b></h4>
{!! $problem->inputformat !!}
@endif

@if (strlen($problem->outputformat))
<h4><b>{{trans('wzoj.output_format')}}:</b></h4>
{!! $problem->outputformat !!}
@endif

@if (strlen($problem->dataconstraints))
<h4><b>{{trans('wzoj.data_constraints')}}:</b></h4>
{!! $problem->dataconstraints !!}
@endif

@if (strlen($problem->sampleinput))
<h4><b>{{trans('wzoj.sample_input')}}:</b></h4>
<pre class="sample_io">
{{$problem->sampleinput}}
</pre>
@endif

@if (strlen($problem->sampleoutput))
<h4><b>{{trans('wzoj.sample_output')}}:</b></h4>
<pre class="sample_io">
{{$problem->sampleoutput}}
</pre>
@endif

@if (isset($download_url))
<p><a href='{{$download_url}}'>{{trans('wzoj.download_attached_file')}}</a></p>
@endif

@if (strlen($problem->hint))
<h4><b>{{trans('wzoj.hints')}}:</b></h4>
{!! $problem->hint !!}
@endif

@if (isset($problemset))
  @can ('view_code_template', $problemset)
    @if (strlen($problem->code_template))
      <h4><b>{{trans('wzoj.code_template')}}:</b></h4>
      <pre><code class="language-clike">{{$problem->code_template}}</code></pre>
    @endif
  @endcan
@endif

{{trans('wzoj.time_limit')}}: {{$problem->timelimit}}ms<br>
{{trans('wzoj.memory_limit')}}: {{$problem->memorylimit}}MB<br>

<hr>
@if (strlen($problem->source))
<p>{{trans('wzoj.source')}}: {{$problem->source}}</p>
@endif
</div>
