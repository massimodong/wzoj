@extends ('layouts.master')

@section ('title')
{{$diyPage->name}}
@endsection

@section ('content')
{!!$diyPage->content!!}
@endsection
