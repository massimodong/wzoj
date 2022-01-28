@extends ('admin.layout')

@section ('title')
{{trans('wzoj.user_logs')}}
@endsection

@section ('content')
  @foreach ($logs as $log)
    {{$log->id}} | {{$log->user_id}} | {{$log->request_ip}} | {{$log->request_port}} | {{$log->level}} | {{trans('log.'.$log->action_name)}} | {{json_encode($log->action_payload)}} </br>
  @endforeach
@endsection
