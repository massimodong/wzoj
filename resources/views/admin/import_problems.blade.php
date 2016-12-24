@extends ('admin.layout')

@section ('title')
import problems
@endsection

@section ('content')
choose file:

<form method=post enctype="multipart/form-data">
{{csrf_field()}}
<b>Import Problem:</b><br />

<input type=file name=fps >
<input type=submit value='Import'>
</form>

@endsection
