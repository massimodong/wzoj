@extends ('layouts.master')

@section ('title')
{{trans('wzoj.problemsets')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('problemsets') !!}

@can ('create',App\Problemset::class)
<form method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>
@endcan

<div class="table-responsive">
  <table class="table">
  <thead>
    <tr>
      <th>{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.name')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problemsets as $problemset)
    <tr>
      <td>{{$problemset->id}}</td>
      <td>
        <a href='/s/{{$problemset->id}}'> {{$problemset->name}} </a>
        @can ('update',$problemset)
        <a href='/s/{{$problemset->id}}/edit'> [{{trans('wzoj.edit')}}] </a>
        @endcan
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection
