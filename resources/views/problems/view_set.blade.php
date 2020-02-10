@extends ('layouts.master')

@section ('title')
{{$problem->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('problem', $problemset, $problem) !!}
<ul class="nav nav-tabs" id="problemTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link  active" id="problem-tab" data-toggle="tab" href="#problem" role="tab" aria-controls="problem" aria-selected="true"> {{trans('wzoj.problem')}} </a>
  </li>
  @if (Auth::check())
  <li class="nav-item">
    <a class="nav-link" id="submit-tab" data-toggle="tab" href="#submit" role="tab" aria-controls="submit" aria-selected="false"> {{trans('wzoj.submit')}} </a>
  </li>
  @endif
  @can ('view_tutorial', $problemset)
    @if (strlen($problem->tutorial))
    <li class="nav-item">
      <a class="nav-link" id="tutorial-tab" data-toggle="tab" href="#tutorial" role="tab" aria-controls="tutorial" aria-selected="true"> {{trans('wzoj.tutorial')}} </a>
    </li>
    @endif
  @endcan
</ul>
<div class="tab-content">
  <div id="problem" class="tab-pane fade show active" role="tabpanel" aria-labelledby="problem-tab">
    <div class="row">
      <div class="col-sm-12">
        @if (Auth::check() && Auth::user()->has_role('admin'))
          <div class="text-right"><a href="/admin/problems/{{$problem->id}}">{{trans('wzoj.edit')}}</a></div>
        @endif
        @include ('partials.showproblem')
      </div>
    </div>
  </div>
  <!-- problem -->

  <div id="submit" class="tab-pane fade" role="tabpanel" aria-labelledby="submit-tab">
    @if ($problem->type <> 3)
    <form id='sol-form' action='/solutions' method='POST' enctype='multipart/form-data'>
      {{csrf_field()}}
      <input name='problemset_id' value='{{$problemset->id}}' hidden>
      <input name='problem_id' value='{{$problem->id}}' hidden>

      <div class="form-group">
        <select class="custom-select" name="language" id="language">
          <option selected value="-1">{{trans('wzoj.language_auto')}}</option>
          <option value='0'>C</option>
          <option value='1'>C++</option>
          <option value='2'>Pascal</option>
          <option value='4'>Python</option>
        </select>
      </div>
      <div class="form-group">
        <input type="file" class="file" name="srcfile" id="srcfile">
      </div>

      <div class="form-group">
        <textarea class="form-control" name="code" id="code" rows="9" placeholder="{{trans('wzoj.submit_helper')}}">{{old('code')}}</textarea>
      </div>

      <button id="submit-btn" type="submit" class="btn btn-primary" onclick="submit_solution();return false;">
        <div id="submit-text">
        {{trans('wzoj.submit')}}
        </div>
        <div id="submit-spin" class="spinner-border spinner-border-sm" role="status" style="display:none">
          <span class="sr-only">Loading...</span>
        </div>
      </button>
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

    <hr>
    <div id="pending-sol"></div>
    @if (Auth::check())
    <div><p><a href="/solutions?user_name={{Auth::user()->name}}&problemset_id={{$problemset->id}}&problem_id={{$problem->id}}">{{trans('wzoj.history_solutions')}}</a></p></div>
    @endif
    <div id="sol-table-template" style="display: none" class="row pb-3">
      <div class="col-12">
        <div class="progress" style="height: 30px;" onclick="progress_click(this);">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary"
            role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            {{trans('wzoj.solution_status_0')}}
          </div>
        </div>
      </div>
      <div class="col-12 pt-1">
        <ul class="list-group list-group-horizontal-sm">
          <li class="list-group-item flex-fill">
            <b>{{trans('wzoj.score')}}: </b>
            <span class="solt-score">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </span>
          </li>
          <li class="list-group-item flex-fill">
            <b>{{trans('wzoj.time_used')}}: </b>
            <span class="solt-time-used">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </span>
          </li>
          <li class="list-group-item flex-fill">
            <b>{{trans('wzoj.memory_used')}} :</b>
            <span class="solt-memory-used">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- submit -->

  @can ('view_tutorial', $problemset)
  <div id="tutorial" class="tab-pane fade" role="tabpanel" aria-labelledby="tutorial-tab">
    {!! $problem->tutorial !!}
  </div>
  @endcan
</div>
<!-- tab-content -->

@endsection

@section ('scripts')
<script>
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

function disable_submit(){
  $('#submit-btn').attr('disabled', true);
  document.getElementById('submit-text').style.display = 'none';
  document.getElementById('submit-spin').style.display = 'block';
}

function enable_submit(){
  $('#submit-btn').attr('disabled', false);
  document.getElementById('submit-text').style.display = 'block';
  document.getElementById('submit-spin').style.display = 'none';
}

function new_pending_solution(id){
  nb = $('#sol-table-template').clone();
  nb.attr('style', 'display: block');
  nb.attr('id', 'solt-' + id);

  nb.data('testcase_num', 0);

  $('#pending-sol').prepend(nb);
}

function submit_solution(){
  if(!detectLanguage()) return false;

  disable_submit();

  $.post('/solutions', $('#sol-form').serialize())
    .done(function(data){
      new_pending_solution(data.id);
      enable_submit();
    })
    .fail(function(data){
      if(data.status == 401){
        window.location.href = '/auth/login';
      }else if(data.responseJSON.err_code == 'blocked'){
        window.location.reload();
      }else if(data.responseJSON.err_code == 'too_frequent'){
        setTimeout(submit_solution, 2000);
      }else{
        addAlertWarning(data.responseJSON.msg);
        enable_submit();
      }
    });
}

function append_ce(pr){
  bar = jQuery("<div></div>");
  bar.addClass("progress-bar");
  bar.addClass("bg-dark");

  bar.attr("role", "progressbar");
  bar.attr("style", "width: 100%");
  bar.attr("aria-valuenow", 100);
  bar.attr("aria-valuemin", 0);
  bar.attr("aria-valuemax", 100);

  bar.html("Compile Error");
  pr.html(bar);
}

function append_testcase(pr, solution, testcase){
  bar = jQuery("<div></div>");
  bar.addClass("progress-bar");
  switch(testcase.verdict){
    case "AC":
      bar.addClass("bg-success");
      break;
    case "WA":
      bar.addClass("bg-danger");
      break;
    case "RE":
    case "TLE":
      bar.addClass("bg-warning");
      break;
    default:
      bar.addClass("bg-info");
  }

  var v = 100 / solution.cnt_testcases;

  bar.attr("role", "progressbar");
  bar.attr("aria-valuenow", v);
  bar.attr("aria-valuemin", 0);
  bar.attr("aria-valuemax", 100);

  pr.append(bar);

  bar.animate({width: v + "%"}, {duration: 300});
}

socket.on('solutions:App\\Events\\SolutionUpdated', function(solution){
  b = $('#solt-' + solution.id);
  if(b.length == 0) return;
  if(solution.status >= 4){
    if(solution.ce){
      append_ce(b.find('.progress'));
    }
    b.find('.solt-score').html(solution.score);
    b.find('.solt-time-used').html(solution.time_used + " ms");
    b.find('.solt-memory-used').html((solution.memory_used / 1024 / 1024).toFixed(2) + " MB");

    b.data("id", solution.id);
    b.find('.progress').addClass("clickable");
  }else{
    if(b.data('testcase_num') == 0){
      b.find('.progress').html("");
    }

    for(var i = b.data('testcase_num'); i < solution.testcases.length; i++){
      append_testcase(b.find('.progress'), solution, solution.testcases[i]);
      b.data('testcase_num', i + 1);
    }
  }
});

function progress_click(e){
  b = $(e).parent().parent();
  if(b.data("id")){
    window.open("/solutions/" + b.data("id"), '_blank');
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
		allowedFileExtensions: ["out"],
		showUpload: false,
		showRemove: false
	}).on("filebatchselected", function(event, files) {
		// trigger upload method immediately after files are selected
		$('#answerfile').fileinput("upload");
	});
	</script>
@endif

@endsection
