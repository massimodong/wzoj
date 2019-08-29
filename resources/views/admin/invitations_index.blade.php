@extends ('admin.layout')

@section ('title')
{{trans('wzoj.invitations')}}
@endsection

@section ('content')

<form method='POST'>
  {{csrf_field()}}
  <button type="submit" class="btn btn-default">+</button>
</form>

<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  {{trans('wzoj.operations')}}
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <a class="dropdown-item" href="#" onclick="invitations_action('set_always_available');"> {{trans('wzoj.set_always_available')}} </a>
    <a class="dropdown-item" href="#" onclick="invitations_action('set_once_available');"> {{trans('wzoj.set_once_available')}} </a>
    <a class="dropdown-item" href="#" onclick="invitations_action('set_non_available');"> {{trans('wzoj.set_non_available')}} </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#" onclick="invitations_action('set_private');"> {{trans('wzoj.set_private')}} </a>
    <a class="dropdown-item" href="#" onclick="invitations_action('set_public');"> {{trans('wzoj.set_public')}} </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#" onclick="invitations_action('download');"> {{trans('wzoj.download')}} </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#" onclick="invitations_action('delete');" style="color: red"> {{trans('wzoj.delete_invitations')}} </a>
  </div>
</div>

<table id="invitations_table" class="table">
  <thead>
    <tr>
      <th><input name="select_all" value="1" type="checkbox"></th>
      <th>{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.description')}}</th>
      <th>{{trans('wzoj.remaining')}}</th>
      <th>{{trans('wzoj.private')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($invitations as $invitation)
    <tr>
      <td></td>
      <td>{{$invitation->id}}</td>
      <td><a href='/admin/invitations/{{$invitation->id}}'>{{$invitation->description}}</a></td>
      <td>{{$invitation->remaining}}</td>
      <td>{{$invitation->private?"Y":""}}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<form id="invitations_form" action="/admin/invitations" method="POST">
{{csrf_field()}}
</form>
@endsection

@section ('scripts')
<script>
function invitations_action( action ){
  $("#invitations_form").append('<input hidden name="_method" value="PUT">');
  $("#invitations_form").append('<input hidden name="action" value="' + action + '">');
  //submit
  var submitInput = $("<input type='submit' />");
  $("#invitations_form").append(submitInput);
  submitInput.trigger("click");
}
var ids = [];
jQuery(document).ready(function($){
  createDatatableWithCheckboxs("invitations_table", ids, "invitations_form");
});
</script>
@endsection
