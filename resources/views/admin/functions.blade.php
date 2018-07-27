@extends ('admin.layout')

@section ('title')
{{trans('wzoj.functions')}}
@endsection

@section ('content')
  <div class="col-xs-12">
    <form action="/admin/functions/broadcast" method="POST" class="form-horizontal">
      {{csrf_field()}}
      <h3>{{trans('wzoj.broadcast')}}</h3>
      <div class="form-group">
        <label for="title" class="col-sm-2 control-label">{{trans('wzoj.title')}}:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="title" name="title" value="">
        </div>
      </div>
      <div class="form-group">
        <label for="content" class="col-sm-2 control-label">{{trans('wzoj.content')}}:</label>
        <div class="col-sm-10">
	  <textarea class="form-control" id="content" name="content" rows="5"></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
        </div>
      </div>
    </form>
    <hr>
  </div>
@endsection
