@extends ('admin.layout')

@section ('title')
{{trans('wzoj.tags')}}
@endsection

@section ('content')

<div>
    <form action='/admin/problem-tags' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>

@foreach ($tags as $tag)
  <form class="form-inline" action="/admin/problem-tags/{{$tag->id}}" method="POST">
    {{csrf_field()}}
    {{method_field('PUT')}}
    #{{$tag->id}}:
    <div class="form-group">
      <input type="text" value="{{$tag->name}}" class="form-control" id="name" name="name" size="5">
    </div>
    <div class="form-group">
      <input type="text" value="{{$tag->aliases}}" class="form-control" id="aliases" name="aliases" size="10">
    </div>
    <div class="form-group">
      <input type="text" value="{{$tag->reference_url}}" class="form-control" id="reference_url" name="reference_url" size="10">
    </div>
    <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
  </form>
@endforeach

@endsection
