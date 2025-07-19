@extends ('admin.layout')

@section ('title')
preview {{$problem->name}}
@endsection

@section ('content')

@include ('partials.showproblem')

@endsection


@section ('scripts')
@include ('partials.mathjax')
@endsection
