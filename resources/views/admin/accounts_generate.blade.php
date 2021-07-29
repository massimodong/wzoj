@extends ('admin.layout')

@section ('title')
{{trans('wzoj.accounts_generate')}}
@endsection

@section ('content')
<form method="POST">
  {{csrf_field()}}
  <div class="form-group">
    <label for="prefix">{{trans('wzoj.prefix')}}</label>
    <input type="text" class="form-control" id="prefix" name="prefix" value="{{old('prefix')}}" required>
  </div>
  <div class="form-group">
    <label for="startno">{{trans('wzoj.start_number')}}</label>
    <input type="text" class="form-control" id="startno" name="startno" value="{{old('startno', 0)}}" required>
  </div>
  <div class="form-group">
    <label for="password_length">{{trans('wzoj.password_length')}}</label>
    <input type="text" class="form-control" id="password_length" name="password_length" value="{{old('password_length', 10)}}" required>
  </div>
  <div class="form-group">
    <label for="class">{{trans('wzoj.class')}}</label>
    <input type="text" class="form-control" id="class" name="class" value="{{old('class')}}">
  </div>
  <div class="form-group">
    <label for="groups_id" class="sr-only">{{trans('wzoj.groups')}}</label>
    <select name="groups_id[]" id="groups_id" class="selectpicker" title="{{trans('wzoj.groups')}}" multiple aria-describedby="helpBlockGroups_id">
      @foreach (\App\Group::all() as $group)
        <option value="{{$group->id}}">{{$group->name}}</option>
      @endforeach
    <select>
    <span id="helpBlockGroups_id" class="text-muted"> {{trans('wzoj.msg_add_groups_when_register')}} </span>
  </div>
  <div class="form-group">
    <label for="fullname" aria-describedby="helpBlockFullname">{{trans('wzoj.fullname')}}</label>
    <span id="helpBlockFullname" class="text-muted"> {{trans('wzoj.msg_one_fullname_per_line')}} </span>
    <textarea class="form-control" id="fullname" name="fullname" rows="5">{{old('fullname')}}</textarea>
  </div>

  <button type="submit" class="btn btn-primary"> {{trans('wzoj.generate')}} </button>
</form>
@endsection
