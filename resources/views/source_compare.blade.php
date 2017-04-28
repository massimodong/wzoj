@extends ('layouts.master')

@section ('title')
{{trans('wzoj.source_compare')}}
@endsection

@section ('head')
@parent
<style type="text/css">
.CodeMirror{
    		height: auto;
    	}
    	.mergely-column{
            height: auto !important;
            width: 45% !important;
        }

</style>
@endsection

@section ('content')
<div class="col-xs-6">
{{trans('wzoj.id')}}: <a href="/solutions/{{$lsolution->id}}" style="color:grey">#{{$lsolution->id}}</a><br>
{{trans('wzoj.user')}}: <a href="/users/{{$lsolution->user_id}}">{{$lsolution->user->name}}</a><br>
{{trans('wzoj.problem')}}: <a href="/s/{{$lsolution->problemset_id}}/{{$lsolution->problem_id}}">{{$lsolution->problem->name}}</a><br>
{{trans('wzoj.score')}}:
@if ($lsolution->score >= 100)
	<span style="color:green"><strong>{{$lsolution->score}}</strong></span>
@else
	<span style="color:red"><strong>{{$lsolution->score}}</strong></span>
@endif
<br>
{{trans('wzoj.time_used')}}: {{$lsolution->time_used}}ms<br>
{{trans('wzoj.memory_used')}}: {{sprintf('%.2f', $lsolution->memory_used / 1024 / 1024)}}MB<br>
{{trans('wzoj.code_length')}}: {{$lsolution->code_length}}B<br>
</div>

<div class="col-xs-6">
{{trans('wzoj.id')}}: <a href="/solutions/{{$rsolution->id}}" style="color:grey">#{{$rsolution->id}}</a><br>
{{trans('wzoj.user')}}: <a href="/users/{{$rsolution->user_id}}">{{$rsolution->user->name}}</a><br>
{{trans('wzoj.problem')}}: <a href="/s/{{$rsolution->problemset_id}}/{{$rsolution->problem_id}}">{{$rsolution->problem->name}}</a><br>
{{trans('wzoj.score')}}:
@if ($rsolution->score >= 100)
	<span style="color:green"><strong>{{$rsolution->score}}</strong></span>
@else
	<span style="color:red"><strong>{{$rsolution->score}}</strong></span>
@endif
<br>
{{trans('wzoj.time_used')}}: {{$rsolution->time_used}}ms<br>
{{trans('wzoj.memory_used')}}: {{sprintf('%.2f', $rsolution->memory_used / 1024 / 1024)}}MB<br>
{{trans('wzoj.code_length')}}: {{$rsolution->code_length}}B<br>
</div>
<div id="compare" class="col-xs-12"></div>
@endsection

@section ('scripts')
<script>
$(document).ready(function () {
	$('#compare').mergely({
		cmsettings: { readOnly: true, lineNumbers: true },
		lhs: function(setValue) {
			setValue({!! json_encode($lsolution->code) !!});
		},
		rhs: function(setValue) {
			setValue({!! json_encode($rsolution->code) !!});
		}
	});
});
</script>
@endsection
