@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problems')}}
@endsection

@section ('content')

<div class="col-xs-12 row">

<div class="col-xs-12">
    <form action='/admin/problems' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>

<div class="col-xs-12" style="height:10px"></div>

<table id="problems_table" class="table table-striped">
  <thead>
    <tr>
      <th style="width: 5%">id</th>
      <th>name</th>
      <th>a</th>
      <th>b</th>
    </tr>
  </thead>
</table>

<form id="problems_form" action="/admin/problems" method="POST">
{{csrf_field()}}
</form>

</div>
@endsection

@section ('scripts')
<script>
$(function() {
    $('#problems_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/problems/dataTablesAjax',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name',
              render : function ( data, type, row, meta ) {
                         return '<a href="/admin/problems/' + row.id + '">' + data + '</a>';
                       }
	    },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }
        ]
    });
});
</script>
@endsection
