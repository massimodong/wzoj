@extends ('layouts.master')

@section ('title')
{{$diyPage->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('diy_page', $diyPage) !!}
{!!$diyPage->content!!}
@endsection
