@extends ('admin.layout')

@section ('title')
{{trans('wzoj.diy_pages')}}
@endsection

@section ('sidebar')
@parent
<li><a href='#' onclick="sendForm($('#diyPage_form')); return false;"> {{trans('wzoj.save')}} </a></li>
@endsection

@section ('content')
<form method="POST" class="form-horizontal" id="diyPage_form">
  {{csrf_field()}}
  {{method_field('PUT')}}
  <div class="form-group">
    <label class="control-label col-sm-2" for="name">{{trans('wzoj.name')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" name="name" value="{{$diyPage->name}}">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="url">{{trans('wzoj.url')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="url" name="url" value="{{$diyPage->url}}">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="url">{{trans('wzoj.content')}}</label>
    <div class="col-sm-10">
      <textarea class="ojeditor" id="content" name="content">{{$diyPage->content}}</textarea>
    </div>
  </div>
</form>
@endsection
