@extends ('layouts.master')

@section ('title')
problemsets
@endsection

@section ('content')

@foreach ($problemsets as $problemset)
<p>
@if ($problemset->type === 'oi')
{{$problemset->contest_start_at}} to {{$problemset->contest_end_at}}
@endif

{{$problemset->type}}
<a href='/s/{{$problemset->id}}'>{{$problemset->name}}</a>
@can ('update',$problemset)
<a href='/s/{{$problemset->id}}/edit'>edit</a>
@endcan
</p>
@endforeach

@can ('create',App\Problemset::class)
<form method='POST'>
{{csrf_field()}}
<button>new problemset</button>
</form>
@endcan

@endsection
