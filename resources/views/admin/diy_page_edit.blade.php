@extends ('admin.layout')

@section ('title')
{{trans('wzoj.diy_pages')}}
@endsection

@section ('sidebar')
@parent
<li><a href='#' onclick="tinymce.triggerSave();sendForm($('#diyPage_form'));return false;"> {{trans('wzoj.save')}} </a></li>
@endsection

@section ('content')
<form method="POST" id="diyPage_form">
  {{csrf_field()}}
  {{method_field('PUT')}}
  <div class="form-group row">
    <label class="col-form-label col-sm-1" for="name">{{trans('wzoj.name')}}</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="name" name="name" value="{{$diyPage->name}}">
    </div>
    <label class="col-form-label col-sm-1" for="url">{{trans('wzoj.url')}}</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="url" name="url" value="{{$diyPage->url}}">
    </div>
    <div class="col-sm-2">
      <button type="submit" class="btn btn-primary"> {{trans('wzoj.save')}} </button>
    </div>
  </div>
  <div class="ojeditor_inline" style="border: 1px dotted black;" id="content">{!! $diyPage->content !!}</div>
</form>
@endsection
