@extends ('admin.layout')

@section ('title')
{{trans('wzoj.functions')}}
@endsection

@section ('content')
<h3>{{trans('wzoj.broadcast')}}</h3>
<form action="/admin/functions/broadcast" method="POST">
  {{csrf_field()}}
  <div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">{{trans('wzoj.title')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="title" name="title" value="">
    </div>
  </div>
  <div class="form-group row">
    <label for="content" class="col-sm-2 col-form-label">{{trans('wzoj.content')}}:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="content" name="content" rows="5"></textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
</form>
<hr>
@endsection
