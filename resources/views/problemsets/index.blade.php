@extends ('layouts.master')

@section ('title')
problemsets
@endsection

@section ('content')

@foreach ($problemsets as $problemset)
<p>
<a href='/s/{{$problemset->id}}'>{{$problemset->name}}</a>
@can ('update',$problemset)
<a href='/s/{{$problemset->id}}/edit'>edit</a>
@endcan
</p>
@endforeach

@endsection
