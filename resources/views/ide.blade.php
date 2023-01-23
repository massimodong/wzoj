@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div name="editor" id="editor" style="position: absolute; top: 0; right: 0; bottom: 0; left: 90px; height: calc(100% - 200px);"></div>
<div style="position: fixed; bottom: 1px; right: 0px; left: 90px; height: 200px; padding-top: 10px">
  <div class="p-3">
    <form id="ide_form" method="POST">
      {{csrf_field()}}
      <div class="form-row">
        <div class="form-group col-3">
          <select class="form-control" id="language" name="language">
            @foreach (explode(",",ojoption("allowed_languages")) as $language)
            <option value='{{intval($language)}}'>{{trans('wzoj.programing_language_'.intval($language))}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-3">
          <button class="btn btn-primary" onclick="simple_judge(); return false;">run</button></br>
        </div>
        <div class="form-group col-2">
          {{trans('wzoj.status')}}: <span id="judge_status"></span>
        </div>
        <div class="form-group col-2">
          {{trans('wzoj.time_used')}}: <span id="time_used"></span> ms
        </div>
        <div class="form-group col-2">
          {{trans('wzoj.memory_used')}}: <span id="memory_used"></span> MB
        </div>
        <div class="form-group col-6">
          <textarea class="form-control" id="input" name="input" rows="3"></textarea>
        </div>
        <div class="form-group col-6">
          <textarea class="form-control" id="output" rows="3"></textarea>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section ('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.14.0/ace.min.js" integrity="sha512-s57ywpCtz+4PU992Bg1rDtr6+1z38gO2mS92agz2nqQcuMQ6IvgLWoQ2SFpImvg1rbgqBKeSEq0d9bo9NtBY0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var editor = ace.edit("editor");
    ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.14.0/')
    editor.session.setMode("ace/mode/c_cpp");

    function simple_judge(){
      var args = $('#ide_form').serialize();
      args += "&code=" + encodeURIComponent(editor.getValue());
      $.post("/ide", args).done(function(data){
          var result = data.output;
          if(data.compileError){
            $('#judge_status').html('CE');
            result = data.compileErrorMessage;
          }else if(data.runtimeError){
            $('#judge_status').html('RE');
            result = data.runtimeErrorMessage;
          }else{
            $('#judge_status').html('OK');
          }
          $('#time_used').html(data.timeused);
          $('#memory_used').html((data.memoryused / 1024).toFixed(2));
          $('#output').val(result);
      })
    }
</script>
@endsection
