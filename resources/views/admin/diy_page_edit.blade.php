@extends ('layouts.master')

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
  <div class="form-group col-xs-6">
    <label class="control-label col-sm-2" for="name">{{trans('wzoj.name')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" name="name" value="{{$diyPage->name}}">
    </div>
  </div>
  <div class="form-group col-xs-6">
    <label class="control-label col-sm-2" for="url">{{trans('wzoj.url')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="url" name="url" value="{{$diyPage->url}}">
    </div>
  </div>
  <div class="ojeditor_inline col-xs-12" style="border: 1px dotted black;" id="content">{!! $diyPage->content !!}</div>
</form>
@endsection
