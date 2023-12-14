@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<form method="POST" class="form-horizontal">
  {{csrf_field()}}
  @include ('partials.captcha.challenge')
  <div class="form-group">
    <div class="col-xs-offset-2 col-xs-10">
      <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
    </div>
  </div>

</form>

@endsection
