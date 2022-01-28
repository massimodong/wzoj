@extends ('admin.layout')

@section ('title')
{{trans('wzoj.user_logs')}}
@endsection

@section ('content')
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>{{trans('wzoj.user')}}</th>
        <th>{{trans('wzoj.ip_address')}}</th>
        <th>{{trans('wzoj.date')}}</th>
        <th>{{trans('wzoj.action')}}</th>
        <th>{{trans('wzoj.data')}}</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($logs as $log)
      <tr>
        <td>{{$log->id}}</td>
        <td>@if (isset($log->user_id)) @include ('partials.user_badge', ['user' => $log->user]) @else - @endif </td>
        <td>{{$log->request_ip}}:{{$log->request_port}}</td>
        <td>{{$log->created_at}}</td>
        <td>{{trans('log.'.$log->action_name)}}({{$log->level}})</td>
        <td>{{json_encode($log->action_payload)}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{$logs->links()}}
@endsection
