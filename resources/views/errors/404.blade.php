@extends ('layouts.master')

@section ('title')
404
@endsection

@section ('content')
<div>{{trans('wzoj.msg_404_error')}}</div>
<div><a href="javascript: history.back();">{{trans('wzoj.back')}}</a></div>
@endsection
