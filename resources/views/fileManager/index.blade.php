@extends ('admin.layout')

@section ('title')
{{$config['title']}}
@endsection

@section ('content')
<div style="padding:10px;">{{trans('wzoj.cur_path')}}:{{$userPath}} <a href="#" onclick="transit('..');return false;">{{trans('wzoj.back')}}</a></div>
<table id="fileManagerTable" class="table table-striped">
<thead>
  <th style="width: 1%"><input name="select_all" value="1" type="checkbox"></th>
  <th>{{trans('wzoj.filename')}}</th>
  <th style="width: 20%">{{trans('wzoj.filesize')}}</th>
  <th style="width: 20%">{{trans('wzoj.fileLastModified')}}</th>
</thead>
<tbody>
  @foreach ($directories as $dir)
  <tr>
    <td></td>
    <td><a href="#" onclick="transit('{{pathinfo($dir)['basename']}}');return false;">{{pathinfo($dir)['basename']}}/</a></td>
    <td></td>
    <td></td>
  </tr>
  @endforeach
  @foreach ($files as $file)
  <tr>
    <td></td>
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

$(document).click(function(){
	if(div_on_preview){
		$('#preview_div').hide();
		div_on_preview = false;
	}
});

var file_names = [];
jQuery(document).ready(function($){
	createDatatableWithCheckboxs("fileManagerTable", file_names, "");
});
</script>
@endsection
