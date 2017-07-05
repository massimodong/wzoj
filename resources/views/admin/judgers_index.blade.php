@extends ('admin.layout')

@section ('title')
{{trans('wzoj.judgers')}}
@endsection

@section ('content')

<div>
    <form action='/admin/judgers' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>

<div class="row">
@foreach ($judgers as $judger)
  <form class="form-inline col-xs-9" action="/admin/judgers/{{$judger->id}}" method="POST">
    {{csrf_field()}}
    {{method_field('PUT')}}
    #{{$judger->id}}:
    <div class="form-group">
      <input type="text" value="{{$judger->name}}" class="form-control" id="name" name="name" size="5">
    </div>
    <div class="form-group">
      <input type="text" value="{{$judger->token}}" class="form-control" id="token" name="token" size="65">
    </div>
    <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
  </form>
  <form class="form-inline col-xs-3" action="/admin/judgers/{{$judger->id}}" method="POST">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
  </form>
@endforeach
</div>

@endsection
