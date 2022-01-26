@extends ('admin.layout')

@section ('title')
{{trans('wzoj.sidepanel')}}
@endsection

@section ('sidebar')
@parent
<li><a href='#' onclick="tinymce.triggerSave();sendForm($('#sidePanel_form'));return false;"> {{trans('wzoj.save')}} </a></li>
@endsection

@section ('content')
<form method="POST">
  {{csrf_field()}}
  {{method_field('PUT')}}
  <div class="form-group row">
    <label class="col-form-label col-sm-2" for="title">{{trans('wzoj.title')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="title" name="title" value="{{$sidePanel->title}}">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-form-label col-sm-2" for="index">{{trans('wzoj.index')}}</label>
    <div class="col-sm-10">
      <input aria-describedby="helpBlockPanelIndex" type="text" class="form-control" id="index" name="index" value="{{$sidePanel->index}}">
      <span id="helpBlockPanelIndex" class="help-block">{{trans('wzoj.msg_panel_index_help')}}</span>
    </div>
  </div>
  <div class="form-group row">
    <label for="content" class="col-sm-2 col-form-label"> {{trans('wzoj.content')}} </label>
    <div class="col-sm-10">
      <textarea class='ojeditor' id='content' name='content'>{{$sidePanel->content}}</textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>

</form>
@endsection
