@extends ('admin.layout')

@section ('title')
problem - {{$problem->name}}
@endsection

@section ('content')

<p><a href='/admin/problems/{{$problem->id}}?preview'>preview</a></p>
<hr>

<form method='POST'>
{{csrf_field()}}
{{method_field('PUT')}}

name:<input name='name' value='{{$problem->name}}'><br>
type:<input name='type' value='{{$problem->type}}'><br>
spj<input type='checkbox' name='spj' value='1' {{$problem->spj?"checked":""}}><br>

timelimit:<input name='timelimit' value='{{$problem->timelimit}}'><br>
memorylimit:<input name='memorylimit' value='{{$problem->memorylimit}}'><br>

description:<br>
<textarea name='description'>{{$problem->description}}</textarea><br>

inputformat:<br>
<textarea name='inputformat'>{{$problem->inputformat}}</textarea><br>

outputformat:<br>
<textarea name='outputformat'>{{$problem->outputformat}}</textarea><br>

sampleinput:<br>
<textarea name='sampleinput'>{{$problem->sampleinput}}</textarea><br>

sampleoutput:<br>
<textarea name='sampleoutput'>{{$problem->sampleoutput}}</textarea><br>

hint:<br>
<textarea name='hint'>{{$problem->hint}}</textarea><br>

source:<input name='source' value='{{$problem->source}}'><br>

<button>save</button>
</form>

<form method='POST'>
{{csrf_field()}}
{{method_field('DELETE')}}
<button>delete</button>
</form>

@endsection
