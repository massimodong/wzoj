@extends ('admin.layout')

@section ('title')
{{trans('wzoj.advanced_settings')}}
@endsection

@section ('content')
<form method="POST">
  {{csrf_field()}}
  <div class="form-group">
    <label for="command">{{trans('wzoj.execute_command')}}:</label>
    <input type="text" class="form-control" id="command" name="command">
    <pre style="min-height:100px">
@foreach ($command_output as $output){{$output}}
@endforeach
    </pre>
  </div>
  <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
</form>
@endsection
