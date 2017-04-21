@extends ('layouts.master')

@section ('title')
{{$problem->name}}
@endsection

@if ($problemset->type <> 'set')
@include ('layouts.contest_header')
@endif

@section ('content')

<ul class="nav nav-tabs" id="problemTabs">
  <li class="active"><a data-toggle="tab" href="#problem"> {{trans('wzoj.problem')}} </a></li>
  @if (Auth::check())
  <li><a data-toggle="tab" href="#submit"> {{trans('wzoj.submit')}} </a></li>
  @endif
</ul>
<div class="tab-content">
  <div id="problem" class="tab-pane in active">
    <div class="col-xs-9 row">
      @include ('layouts.showproblem')
    </div>
    <div class="col-xs-3">
      <div style="height:85px"></div>
      <div class="panel panel-default">
        <div class="panel-heading">{{trans('wzoj.status')}}</div>
        <div class="panel-body">
	  {{trans('wzoj.count_submit')}}/{{trans('wzoj.count_ac')}}: {{$cnt_submit}}/{{$cnt_ac}}
	  <hr>
	  <div class="limited_text">
	    @foreach ($best_solutions as $index => $solution)
	      @if ($index == 0)
	        <span class="label label-success">1</span>
	      @elseif ($index == 1)
	        <span class="label label-primary">2</span>
	      @else
	        <span class="label label-info">3</span>
	      @endif
	      <a href="/users/{{$solution->user->id}}">{{$solution->user->name}}</a><br>
	      <span class="best_solution_status">
	        <a style="color:grey" href="/solutions/{{$solution->id}}">#{{$solution->id}}</a>
	        @if ($solution->score == 100)
	        <span style="color:green"><strong>{{$solution->score}}</strong></span>
	        @else
	        <span style="color:red"><strong>{{$solution->score}}</strong></span>
	        @endif
	        {{$solution->time_used}}ms
	        {{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB
	      </span><br>
	      <div class="top-buffer-sm"></div>
	    @endforeach
	  </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">{{trans('wzoj.tags')}}</div>
        <div class="panel-body">
	  @foreach ($tags as $tag)
	    @if ($tag->reference_url <> '')
	      <a href="{{$tag->reference_url}}"><span class="label label-default">{{$tag->name}}</span></a>
	    @else
	      <span class="label label-default">{{$tag->name}}</span>
	    @endif
	  @endforeach
	</div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading"><a href="/forum?tags[]=p{{$problem->id}}">{{trans('wzoj.forum')}}</a></div>
        <div class="panel-body">
	  @foreach ($topics as $topic)
	    <a href="/forum/{{$topic->id}}">{{$topic->title}}</a><br>
	  @endforeach
	</div>
      </div>
    </div>
  </div>
  <!-- problem -->

  <div id="submit" class="tab-pane">
    <div class="top-buffer-sm"></div>
    @if ($problem->type <> 3)
    <form action='/solutions' method='POST' enctype='multipart/form-data'>
      {{csrf_field()}}
      <input name='problemset_id' value='{{$problemset->id}}' hidden>
      <input name='problem_id' value='{{$problem->id}}' hidden>
      <div class='form-group row'>
        <label for='language' class='col-xs-1 col-form-label'> {{trans('wzoj.language')}}: </label>
	<div class='col-xs-3'>
          <select name='language' id='language' class='form-control input-sm'>
      	    <option value='-1'>{{trans('wzoj.auto')}}</option>
      	    <option value='0'>C</option>
	    <option value='1'>C++</option>
	    <option value='2'>Pascal</option>
	    <option value='4'>Python</option>
          </select>
	</div>
	<label for='srcfile' class='col-xs-1 col-form-label'> {{trans('wzoj.choosefile')}}</label>
	<div class='col-xs-7'>
	  <input type="file" class="file" name="srcfile" id="srcfile">
	</div>
      </div>
      <!-- form-group -->

      <div class='form-group'>
        <textarea class="form-control" name="code" id="code" rows="9">{{old('code')}}</textarea>
      </div>
      <!-- form-group -->

      <button type="submit" class="btn btn-primary" onclick="return detectLanguage()"> {{trans('wzoj.submit')}} </button>
    </form>
    @else
      @if (count($answerfiles))
	{{trans('wzoj.uploaded_files')}}:<br>
	@foreach ($answerfiles as $answerfile)
	  {{$answerfile->filename}}.out<br>
	@endforeach
      @endif
    <form action='/solutions' method='POST' enctype='multipart/form-data'>
      {{csrf_field()}}
      <input name='problemset_id' value='{{$problemset->id}}' hidden>
      <input name='problem_id' value='{{$problem->id}}' hidden>
      <input name='language' value='0' hidden>
      <input name='code' value='THIS SOLUTION HAS NOT CODE' hidden>
      
      <input type="file" class="file" name="answerfile" id="answerfile" multiple>
      <button type="submit" class="btn btn-primary"> {{trans('wzoj.submit_and_judge')}} </button>
    </form>
    @endif
  </div>
  <!-- submit -->
</div>
<!-- tab-content -->

@endsection

@section ('scripts')
<script>
selectHashTab();
function detectLanguage(){
	if($('#language').val() != -1) return true;
	if($('#srcfile').val()){ //uploading file
		var str = $('#srcfile').val();
		var ext = str.substr(str.lastIndexOf('.') + 1);
		//console.log('ext:' + ext);
		switch(ext){
			case 'c':
				$('#language').val(0);
				return true;
			case 'cpp':
			case 'cc':
			case 'cxx':
				$('#language').val(1);
				return true;
			case 'pas':
				$('#language').val(2);
				return true;
			case 'py':
				$('#language').val(4);
				return true;
			default:
				alert('{{trans('wzoj.msg_unable_to_detect_language')}}');
				return false;
		}
	}else{
		var lang = codeDetectLanguage($('#code').val());
		if(lang == -1){
			alert('{{trans('wzoj.msg_unable_to_detect_language')}}');
			return false;
		}else{
			$('#language').val(lang);
			return true;
		}
	}
}
</script>
@if ($problem->type == 3)
	<script>
	$('#answerfile').fileinput({
		'uploadUrl': "/solutions/answerfile",
		'uploadExtraData': {
			_token: csrf_token,
			problemset_id: {{$problemset->id}},
			problem_id: {{$problem->id}}
		},
		showUpload: false,
		showRemove: false
	}).on("filebatchselected", function(event, files) {
		// trigger upload method immediately after files are selected
		$('#answerfile').fileinput("upload");
	});
	</script>
@endif

@endsection
