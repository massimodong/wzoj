@extends ('admin.layout')

@section ('title')
problems
@endsection

@section ('content')

@foreach ($problems as $problem)
	<p><a href='/admin/problems/{{$problem->id}}'>{{$problem->name}}</a></p>
@endforeach

<form method='POST'>
{{csrf_field()}}
<button>new problem</button>
</form>

@endsection
