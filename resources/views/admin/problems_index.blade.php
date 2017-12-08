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
      <th style="width: 5%">{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th style="width: 10%">{{trans('wzoj.type')}}</th>
      <th style="width: 5%">spj</th>
      <th style="width: 10%">{{trans('wzoj.source')}}</th>
      <th style="width: 10%">{{trans('wzoj.tags')}}</th>
      <th style="width: 15%">{{trans('wzoj.problemsets')}}</th>
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
            { data: 'type', name: 'type',
	      render: function ( data, type, row, meta) {
	                return TRANS['problem_type_' + data];
	              }
	    },
            { data: 'spj', name: 'spj',
	      render: function ( data, type, row, meta) {
	                return data?"Y":"N";
	              }
	    },
	    { data: 'source', name: 'name' },
	    { data: 'tags', name: 'tags.name',
	       render: function ( data, type, row, meta) {
			       console.log(row.problemsets);
			       if(data.length >= 1){
				       ret = data[0].name;
			       }else{
				       ret = '';
			       }

			       for(i = 1; i < data.length; ++i){
				       ret = ret + ' ' + data[i].name;
			       }
			       return ret;
		       }
	    },
	    { data: 'problemsets', name: 'problemsets.name',
	      render: function ( data, type, row, meta) {
			      if(data.length >= 1){
				       ret = data[0].name;
			       }else{
				       ret = '';
			       }

			       for(i = 1; i < data.length; ++i){
				       ret = ret + ' ' + data[i].name;
			       }
			       return ret;
		      }
	    }
        ]
    });
});
</script>
@endsection
