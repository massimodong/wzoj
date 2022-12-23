@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div id="editor" style="position: absolute; top: 0; right: 0; bottom: 0; left: 200px;">
</div>
@endsection

@section ('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.14.0/ace.min.js" integrity="sha512-s57ywpCtz+4PU992Bg1rDtr6+1z38gO2mS92agz2nqQcuMQ6IvgLWoQ2SFpImvg1rbgqBKeSEq0d9bo9NtBY0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var editor = ace.edit("editor");
    ace.config.set('basePath', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.14.0/')
    editor.session.setMode("ace/mode/c_cpp");
</script>
@endsection
