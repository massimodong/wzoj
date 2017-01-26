@extends ('layouts.master')

@section ('title')
{{trans('wzoj.register')}}
@endsection

@section ('content')
<form method="POST" action="/auth/register?token={{$invitation->token}}">
{!! csrf_field() !!}

<div class="col-xs-offset-3 col-xs-6">
  <div class="form-group row">
    <label for="name" class="col-xs-2 col-form-label">{{trans('wzoj.username')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}" required>
    </div>
  </div>

  <div class="form-group row">
    <label for="fullname" class="col-xs-2 col-form-label">{{trans('wzoj.fullname')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="text" name="fullname" id="fullname" 
      @if ($invitation->fullname <> '')
	value="{{$invitation->fullname}}" disabled
      @else
        value="{{old('fullname')}}"
      @endif
      required>
    </div>
  </div>

  <div class="form-group row">
    <label for="class" class="col-xs-2 col-form-label">{{trans('wzoj.class')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="text" name="class" id="class"
      @if ($invitation->class <> '')
	value="{{$invitation->class}}" disabled
      @else
	value="{{ old('class')}}"
      @endif
      required>
    </div>
  </div>

  <div class="form-group row">
    <label for="email" class="col-xs-2 col-form-label">{{trans('wzoj.email')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="email" name="email" id="email" value="{{old('email')}}" required>
    </div>
  </div>

  <div class="form-group row">
    <label for="password" class="col-xs-2 col-form-label">{{trans('wzoj.password')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="password" name="password" id="password" value="{{old('password')}}" required>
    </div>
  </div>

 <div class="form-group row">
    <label for="password_confirmation" class="col-xs-2 col-form-label">{{trans('wzoj.password_confirmation')}}</label>
    <div class="col-xs-10">
      <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="{{old('password_confirmation')}}" required>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">{{trans('wzoj.register')}}</button>

</div>
</form>
@endsection
