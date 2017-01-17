@extends ('layouts.master')

@section ('title')
{{$problem->name}}
@endsection

@section ('sidebar')
<li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
<li id='problems_sidebar'><a href="/s/{{$problemset->id}}"> {{trans('wzoj.problem')}} </a></li>
<li id='solutions_sidebar'><a href="/solutions?problemset_id={{$problemset->id}}"> {{trans('wzoj.solutions')}} </a></li>
<li id='submit_sidebar'><a href='/s/{{$problemset->id}}/{{$problem->id}}/submit'>submit</a></li>
@endsection

@section ('content')

@include ('layouts.showproblem')

<div class="col-lg-12">
<a href='/s/{{$problemset->id}}/{{$problem->id}}/submit'><button type="button" class="btn btn-info">submit</button></a>
</div>
@endsection
