@extends ('admin.layout')

@section ('title')
groups
@endsection

@section ('content')

@foreach ($groups as $group)
	<p><a href='/admin/groups/{{$group->id}}'>{{$group->name === ''?'(unnamed)':$group->name}}</a></p>
@endforeach

<form method='POST'>
{{csrf_field()}}
<button>new group</button>
</form>

@endsection
