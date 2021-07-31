@extends ('admin.layout')

@section ('title')
{{trans('wzoj.update_system')}}
@endsection

@section ('content')
<h3>{{trans('wzoj.update_system')}}</h3>
<p>{{trans('wzoj.update_system_guide')}}<a href="https://github.com/massimodong/wzoj/wiki/%E6%9B%B4%E6%96%B0%E6%8C%87%E5%8D%97" target="_blank">link</a></p>
<hr>
<form method='POST' enctype='multipart/form-data'>
{{csrf_field()}}
<input type="file" name="pkg">
<input type="text" name="version_tag" id="version_tag">
<input type="text" name="version_id" id="version_id">
<button>update!</button>
<form>
<p>Please download the new version below and upload<br><a id="url"></a></p>
@endsection

@section ('scripts')
<script>
$.get("https://api.github.com/repos/massimodong/wzoj/releases/latest").done(function(release){
  console.log(release);
  $('#version_tag').val(release.tag_name);
  $('#version_id').val(release.id);
  $('#url').attr('href', release.tarball_url);
  $('#url').html(release.tarball_url);
});
</script>
@endsection
