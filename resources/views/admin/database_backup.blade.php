@extends ('admin.layout')

@section ('title')
{{trans('wzoj.database_backup')}}
@endsection

@section ('content')
<div>
  <p style="color:red">{{trans('wzoj.msg_database_backup_warning')}}</p>
  <p>{{trans('wzoj.database_backup_file_size')}}:{{sprintf('%.2f', $tot_size / 1024 / 1024 / 1024)}}GB
    <a href='#' onclick='restrict_size();return false;'>{{trans('wzoj.backup_restrict_size')}}</a></p>
</div>

<form action="/admin/options" method="POST" class="form-inline">
  {{csrf_field()}}
  <div class="form-group">
    <label for="database_size_limit">{{trans('wzoj.database_size_limit')}}:</label>
    <input type="text" class="form-control" id="database_size_limit" name="database_size_limit" value="{{ojoption('database_size_limit')}}">
  </div>
  <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
</form>

<table class='table'>
  <thead>
    <tr>
      <th>{{trans('wzoj.backup_time')}}</th>
      <th>{{trans('wzoj.file_size')}}</th>
      <th>{{trans('wzoj.operations')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($backups as $backup)
    <tr>
      <td>{{ojShortTime($backup['time'])}}</td>
      <td>{{sprintf('%.2f', $backup['size'] / 1024 / 1024)}}MB</td>
      <td><a href='#' onclick="delete_backup({{$backup['time']}});return false;">{{trans('wzoj.delete')}}</a></td>
    </tr>
  @endforeach
  </tbody>
</table>

<form id="restrict_size_form" method="POST" action="/admin/database-backup/restrict-size">
{{csrf_field()}}
</form>

<form id="delete_backup_form" method="POST" action="/admin/database-backup">
{{csrf_field()}}
{{method_field('DELETE')}}
<input id="delete_backup_id" name="delete_backup_id" value="" hidden>
</form>
@endsection

@section ('scripts')
<script>
function restrict_size(){
	if(!confirm('{{trans('wzoj.msg_backup_restrict_size')}}')){
		return;
	}
	$('#restrict_size_form').submit();
}
function delete_backup( time ){
	if(!confirm('{{trans('wzoj.msg_confirm_delete_backup')}}')){
		return;
	}
	$('#delete_backup_id').val(time);
	$('#delete_backup_form').submit();
}
</script>
@endsection
