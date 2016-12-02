@extends ('layouts.master')

@section ('title')
submit
@endsection

@section ('content')

<h3>ps:{{$problemset->name}}</h3>
<h3>p:{{$problem->name}}</h3>

<form action='/solutions' method='POST'>
{{csrf_field()}}

<input name='problemset_id' value='{{$problemset->id}}' hidden>
<input name='problem_id' value='{{$problem->id}}' hidden>

language:<input name='language'><br>
code:<br><textarea name='code'></textarea><br>

<button>submit</button>

</form>

@endsection
