@extends ('admin.layout')

@section ('title')
invitations
@endsection

@section ('content')

<div class='col-lg-12'>

<form method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>

<table class="table table-striped">
<thead>
    <tr>
        <th>id</th>
        <th>description</th>
	<th>remaining</th>
	<th>private</th>
    </tr>
</thead>
<tbody>
@foreach ($invitations as $invitation)
    <tr>
    	<td>{{$invitation->id}}</td>
	<td><a href='/admin/invitations/{{$invitation->id}}'>{{$invitation->description}}</a></td>
	<td>{{$invitation->remaining}}</td>
	<td>{{$invitation->private?"Y":""}}</td>
    </tr>
@endforeach
</tbody>

</table>

</div>
@endsection
