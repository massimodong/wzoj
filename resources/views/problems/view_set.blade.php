@extends ('layouts.master')

@section ('title')
problem {{$problem->name}}
@endsection

@section ('content')

@include ('layouts.showproblem')

<p><a href='./{{$problem->id}}/submit'>submit</a></p>

@endsection
