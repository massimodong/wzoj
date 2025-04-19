@extends ('layouts.master')

@section ('title')
{{trans('wzoj.user')}} - {{$user->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('user', $user) !!}

{{trans('wzoj.not_displayed_for_phone')}}

@endsection
