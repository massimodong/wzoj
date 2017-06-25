@extends ('admin.layout')

@section ('title')
{{trans('wzoj.notices')}}
@endsection

@section ('content')
<form action="/admin/options" method="POST" class="form-horizontal">
  {{csrf_field()}}
  <div class="form-group">
    <label for="notice" class="col-sm-2 control-label">{{trans('wzoj.notices')}}:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="notice" name="notice" rows="10">{{ojoption('notice')}}</textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
    </div>
  </div>
</form>
@endsection
