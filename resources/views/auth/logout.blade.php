@extends ('layouts.master')

@section ('title')
{{trans('wzoj.logout')}}
@endsection

@section ('content')
<form method="POST" class="form-signin">
{!! csrf_field() !!}
<h3>{{trans('wzoj.confirm_logout')}}</h3>
<button class="btn btn-lg btn-primary btn-block" type="submit">{{trans('wzoj.logout')}}</button>
</form>
@endsection
