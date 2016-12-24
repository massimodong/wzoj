@extends ('layouts.master')

@section ('title')
{{$problem->name}}
@endsection

@section ('sidebar')
@parent
<li id='submit_sidebar'><a href='/s/{{$problemset->id}}/{{$problem->id}}/submit'>submit</a></li>
@endsection

@section ('content')

@include ('layouts.showproblem')

<div class="col-lg-12">
<a href='/s/{{$problemset->id}}/{{$problem->id}}/submit'><button type="button" class="btn btn-info">submit</button></a>
</div>
@endsection
