@extends ('admin.layout')

@section ('title')
{{trans('wzoj.import_problems')}}
@endsection

@section ('content')
{{trans('wzoj.choosefile')}}:

<form method=post enctype="multipart/form-data">
{{csrf_field()}}

<input type=file name=fps >
<input type=submit value='Import'>
</form>

@endsection
