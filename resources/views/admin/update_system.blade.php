@extends ('admin.layout')

@section ('title')
Update System
@endsection

@section ('content')
<form method="POST">
{{csrf_field()}}
<button>update!</button>
<form>
@endsection
