@extends ('admin.layout')

@section ('title')
{{trans('wzoj.update_system')}}
@endsection

@section ('content')
<form method='POST' enctype='multipart/form-data'>
{{csrf_field()}}
<input type="file" name="pkg"></input>
<input type="text" name="version_tag" placeholder="version_tag"></input>
<input type="text" name="version_name" placeholder="version_name"></input>
<button>update!</button>
<form>
@endsection
