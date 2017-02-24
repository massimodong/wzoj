@extends ('admin.layout')

@section ('title')
{{trans('wzoj.invitations')}}
@endsection

@section ('content')

<div class='col-xs-12 row'>

<form method='POST' class='col-xs-6'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>

<div class="col-xs-6">
  <div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true    " aria-expanded="true">
    {{trans('wzoj.operations')}}
    <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#" onclick="invitations_action('set_always_available');"> {{trans('wzoj.set_always_available')}} </a></li>
      <li><a href="#" onclick="invitations_action('set_once_available');"> {{trans('wzoj.set_once_available')}} </a></li>
      <li><a href="#" onclick="invitations_action('set_non_available');"> {{trans('wzoj.set_non_available')}} </a></li>
      <li role="separator" class="divider"></li>
      <li><a href="#" onclick="invitations_action('set_private');"> {{trans('wzoj.set_private')}} </a></li>
      <li><a href="#" onclick="invitations_action('set_public');"> {{trans('wzoj.set_public')}} </a></li>
      <li role="separator" class="divider"></li>
      <li><a href="#" onclick="invitations_action('delete');" style="color: red"> {{trans('wzoj.delete_invitations')}} </a></li>
    </ul>
  </div>
</div>

<table id="invitations_table" class="table table-striped">
<thead>
    <tr>
        <th style="width: 1%;"><input name="select_all" value="1" type="checkbox"></th>
        <th style="width: 5%;">{{trans('wzoj.id')}}</th>
        <th>{{trans('wzoj.description')}}</th>
	<th style="width: 10%;">{{trans('wzoj.remaining')}}</th>
	<th style="width: 10%;">{{trans('wzoj.private')}}</th>
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

</div>
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
