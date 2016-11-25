@extends ('admin.layout')

@section ('title')
preview {{$problem->name}}
@endsection

@section ('content')

@include ('layouts.showproblem')

@endsection
