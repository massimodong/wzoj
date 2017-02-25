@extends ('admin.layout')

@section ('title')
{{trans('wzoj.update_system')}}
@endsection

@section ('content')
<form method="POST">
{{csrf_field()}}
<button>update!</button>
<form>
@endsection
