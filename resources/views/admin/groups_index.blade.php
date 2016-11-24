@extends ('admin.layout')

@section ('title')
groups
@endsection

@section ('content')

@foreach ($groups as $group)
	<p><a href='/admin/groups/{{$group->id}}'>{{$group->name}}</a></p>
@endforeach

@endsection
