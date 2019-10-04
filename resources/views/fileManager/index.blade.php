@extends ('fileManager.layout')

@section ('title')
{!! $config['title'] !!}
@endsection

@section ('content')
<div style="padding:10px;">{{trans('wzoj.cur_path')}}:{{$userPath}} <a href="#" onclick="transit('..');return false;">{{trans('wzoj.back')}}</a></div>

@if ($can_modify)
<div>
  <form method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input hidden name="action" value="upload">
    <input id="fileManager_upload_input" name="files[]" type="file" class="file" multiple>
  </form>
</div>
@else
<div></div>
@endif

<form id="fileManager_form" class="form-inline"></form>
<div>
  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{trans('wzoj.operations')}}
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <a class="dropdown-item" href="#" onclick="fileManager_action('download', 'GET');return false;">{{trans('wzoj.download')}}</a>
      @if ($can_modify)
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="#" onclick="confirm('{{trans('wzoj.msg_confirm_delete_file')}}')&&fileManager_action('delete');return false;" style="color: red">{{trans('wzoj.delete')}}</a></li>
      @endif
    </ul>
  </div>
</div>

<table id="fileManagerTable" class="table table-striped">
<thead>
  <th style="width: 1%"><input name="select_all" value="1" type="checkbox"></th>
  <th style="display:none">{{trans('wzoj.filename')}}</th>
  <th>{{trans('wzoj.filename')}}</th>
  <th style="width: 20%">{{trans('wzoj.filesize')}}</th>
  <th style="width: 20%">{{trans('wzoj.fileLastModified')}}</th>
</thead>
<tbody>
  @foreach ($directories as $dir)
  <tr>
    <td></td>
    <td style="display:none">{{pathinfo($dir)['basename']}}</td>
    <td><a href="#" onclick="transit('{{pathinfo($dir)['basename']}}');return false;">{{pathinfo($dir)['basename']}}/</a></td>
    <td></td>
    <td></td>
  </tr>
  @endforeach
  @foreach ($files as $file)
  <tr>
    <td></td>
    <td style="display:none">{{pathinfo($file)['basename']}}</td>
    <td><span style="cursor:pointer" onclick="preview('{{pathinfo($file)['basename']}}')">{{pathinfo($file)['basename']}}</span></td>
    <td>{{Storage::disk($config['disk'])->size($file)}}</td>
    <td>{{date('M d H:i', Storage::disk($config['disk'])->lastModified($file))}}</td>
  </tr>
  @endforeach
</tbody>
</table>

<form id="transit_form" method="GET">
</form>

<div id="preview_div" class="floating-div" style="display:none">
</div>
@endsection

@section ('scripts')
<script>
function transit(path){
	$('#transit_form').append("<input name='path' value='" + "{{$userPath}}" + path + "' hidden></input>");
	$('#transit_form').submit();
}

var div_on_preview = false;
function preview(path){
	if(div_on_preview) return;
	$('#preview_div').html('<iframe height="500"  allowTransparency="true" frameborder="0" scrolling="yes" style="width:100%;" src="' +
		       window.location.origin + window.location.pathname + '?file=' + '{{$userPath}}' + path +
		       '" type= "text/javascript"></iframe>');
	$('#preview_div').show();
	setTimeout('div_on_preview = true', 1000);
}

function fileManager_action( action, method = 'POST'){
	if(file_names.length == 0) return;
	$('#fileManager_form').html('');
	$('#fileManager_form').attr('method', method);
	if(method == 'POST'){
		$('#fileManager_form').append('<input hidden name="_token" value="{{csrf_token()}}">')
	}
	$('#fileManager_form').append('<input hidden name="action" value="' + action + '">');
	$('#fileManager_form').append("<input hidden name='path' value='" + "{{$userPath}}" + "'>");
	var submitInput = $("<input style='display:none' type='submit' />");
	$("#fileManager_form").append(submitInput);
        submitInput.trigger("click");
}

$(document).click(function(){
	if(div_on_preview){
		$('#preview_div').hide();
		div_on_preview = false;
	}
});

var file_names = [];
jQuery(document).ready(function($){
	createDatatableWithCheckboxs("fileManagerTable", file_names, "fileManager_form");
});

$('#fileManager_upload_input').fileinput({
	dropZoneEnabled: false,
	showPreview: false,
	showRemove: false,
});
</script>
@endsection
