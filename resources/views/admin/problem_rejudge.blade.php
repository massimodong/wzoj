@extends ('admin.layout')

@section ('title')
Rejudge
@endsection

@section ('content')
<form method='POST'>
{{csrf_field()}}

solution_id:<input name='solution_id'><br>

problemset_id:<input name='problemset_id'><br>

<button>submit</button>

</form>
@endsection
