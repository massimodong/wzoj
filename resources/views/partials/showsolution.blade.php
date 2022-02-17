<tr>
  @if ($solution->problem->type == 3)
    @can ('view_code', $solution)
      <td><a href='/solutions/{{$solution->id}}?answerfile={{$testcase->filename}}' title="{{trans('wzoj.download_answerfile')}}">{{$testcase->filename}}</a></td>
    @else
      <td>{{$testcase->filename}}</td>
    @endcan
  @else
    <td>{{$testcase->filename}}</td>
  @endif
    <td>{{$testcase->score}}</td>
  @if ($solution->problem->type != 3)
    <td>{{$testcase->time_used}}ms</td>
    <td>{{sprintf('%.2f', $testcase->memory_used / 1024 / 1024)}}MB</td>
  @endif
    <td>{{$testcase->verdict}}</td>
    <td>{{$testcase->checklog}}</td>
</tr>
