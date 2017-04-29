@extends ('layouts.master')

@section ('title')
403
@endsection

@section ('content')
<div>{{trans('wzoj.msg_403_error')}}</div>
<div><a href="javascript: history.back();">{{trans('wzoj.back')}}</a></div>
@endsection
