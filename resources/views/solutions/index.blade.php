@extends ('layouts.master')

@section ('title')
solutions
@endsection

@section ('content')

@foreach ($solutions as $solution)
{{$solution->user->name}}:<a href='/solutions/{{$solution->id}}'>{{$solution->problem->name}}</a>
status:{{$solution->status}} score:{{$solution->score}}<br>
@endforeach

@endsection
