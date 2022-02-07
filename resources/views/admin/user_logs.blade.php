@extends ('admin.layout')

@section ('title')
{{trans('wzoj.user_logs')}}
@endsection

@section ('content')
  <form method='GET' class="form-inline">

    <label for="levels" class="sr-only"></label>
    <select name="levels[]" id="levels" class="selectpicker mb-2 mr-2" title="{{trans('wzoj.log_level')}}" multiple>
    @for ($i=1;$i<=3;$i++)
      <option value="{{$i}}">{{trans('wzoj.log_level_'.$i)}}</option>
    @endfor
    </select>

    <label for="uids" class="sr-only"></label>
    <select name="uids[]" id="uids" class="selectpicker mb-2 mr-2" data-live-search="true" title="{{trans('wzoj.search_user')}}" multiple>
    @foreach (\App\User::orderBy('id', 'asc')->get() as $user)
      <option value="{{$user->id}}">{{$user->id}}-{{$user->name}}</option>
    @endforeach
    </select>

    <label for="actions" class="sr-only"></label>
    <select name="actions[]" id="actions" class="selectpicker mb-2 mr-2" data-live-search="true" title="{{trans('wzoj.actions')}}" multiple>
    @foreach (Lang::get('log') as $key => $value)
      <option value="{{$key}}">{{$value}}</option>
    @endforeach
    </select>

    <button type="submit" class="btn btn-primary mb-2">{{trans('wzoj.search')}}</button>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>{{trans('wzoj.log_level')}}</th>
        <th>{{trans('wzoj.user')}}</th>
        <th>{{trans('wzoj.ip_address')}}</th>
        <th>{{trans('wzoj.date')}}</th>
        <th>{{trans('wzoj.action')}}</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($logs as $log)
      <tr>
        <td>{{$log->id}}</td>
        <td>{{trans('wzoj.log_level_'.$log->level)}}</td>
        <td>@if (isset($log->user_id)) @include ('partials.user_badge', ['user' => $log->user]) @else - @endif </td>
        <td>{{$log->request_ip}}:{{$log->request_port}}</td>
        <td>{{$log->created_at}}</td>
        @if (count($log->action_payload))
        <td><a href="#" data-toggle="modal" data-target="#{{'log-modal-'.$log->id}}">{{trans('log.'.$log->action_name)}}</a></td>
        @else
        <td>{{trans('log.'.$log->action_name)}}</td>
        @endif
      </tr>
    @endforeach
    </tbody>
  </table>

  {{$logs->links()}}
  @foreach ($logs as $log)
  <div class="modal fade" id={{"log-modal-".$log->id}} data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{trans('wzoj.user_logs')}} #{{$log->id}}: {{trans('log.'.$log->action_name)}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
            <tbody>
            @foreach ($log->action_payload as $a => $b)
              <tr>
                <th>{{$a}}</th>
                <td>{{$b}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endforeach

@endsection
