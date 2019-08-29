@extends ('admin.layout')

@section ('title')
{{trans('wzoj.invitations_generate')}}
@endsection

@section ('content')
<form method="POST">
  {{csrf_field()}}
  <div class="form-group">
    <label for="prefix">{{trans('wzoj.prefix')}}</label>
    <input type="text" class="form-control" id="prefix" name="prefix" required>
  </div>
  <div class="form-group">
    <label for="class">{{trans('wzoj.class')}}</label>
    <input type="text" class="form-control" id="class" name="class">
  </div>
  <div class="form-group">
    <label for="remaining" aria-describedby="helpBlockRemaining">{{trans('wzoj.remaining')}}</label>
    <span id="helpBlockRemaining" class="text-muted"> {{trans('wzoj.msg_explain_remaining')}} </span>
    <input type="text" class="form-control" id="remaining" name="remaining" value="1" required>
  </div>
  <div class="form-group">
    <label for="private">{{trans('wzoj.private')}}</label>
    <select class="form-control" id="private" name="private">
      <option value="0"> {{trans('wzoj.no')}} </option>
      <option value="1"> {{trans('wzoj.yes')}} </option>
    </select>
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
    <textarea class="form-control" id="fullname" name="fullname" rows="5"></textarea>
  </div>

  <button type="submit" class="btn btn-primary"> {{trans('wzoj.generate')}} </button>
</form>
@endsection
