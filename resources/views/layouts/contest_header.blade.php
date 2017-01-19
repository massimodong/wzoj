@section ('site_title')
{{$problemset->name}}
@endsection
@section ('home_href')
'/s/{{$problemset->id}}'
@endsection
@section ('sidebar')
<li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
<li id='problems_sidebar'><a href="/s/{{$problemset->id}}"> {{trans('wzoj.problem')}} </a></li>
<li id='solutions_sidebar'><a href="/solutions?problemset_id={{$problemset->id}}"> {{trans('wzoj.solutions')}} </a></li>
<li id='ranklist_sidebar'><a href="/s/{{$problemset->id}}/ranklist"> {{trans('wzoj.ranklist')}} </a></li>
@endsection
