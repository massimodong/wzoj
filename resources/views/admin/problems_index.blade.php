@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problems')}}
@endsection

@section ('content')

<div class="col-xs-12 row">

@can ('create',App\Problem::class)
<div class="col-xs-12">
    <form action='/admin/problems' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>
@endcan

<div class="col-xs-12" style="height:10px"></div>

<table id="problems_table" class="table">
  <thead>
    <tr>
      <th style="width: 5%">{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th style="width: 10%">{{trans('wzoj.type')}}</th>
      <th style="width: 5%">spj</th>
      <th style="width: 10%">{{trans('wzoj.source')}}</th>
      <th style="width: 10%">{{trans('wzoj.tags')}}</th>
      <th style="width: 10%">{{trans('wzoj.pass_rate')}}</th>
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
        iDisplayLength: 100,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name',
              render : function ( data, type, row, meta ) {
                         return '<a href="/admin/problems/' + row.id + '">' + escapeHtml(data) + '</a> <a href="/admin/problems/' + row.id + '/data">[' + TRANS['testdata'] + ']</a>';
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
				       ret = escapeHtml(data[0].name);
			       }else{
				       ret = '';
			       }

			       for(i = 1; i < data.length; ++i){
				       ret = ret + ' ' + escapeHtml(data[i].name);
			       }
			       return ret;
		       }
	    },
	    { render: function ( data, type, row, meta) {
				return String(row.cnt_ac) + '/' + String(row.cnt_submit);
	 	      }
	    },
	    { data: 'problemsets', name: 'problemsets.name',
	      render: function ( data, type, row, meta) {
			      if(data.length >= 1){
				       ret = escapeHtml(data[0].name);
			       }else{
				       ret = '';
			       }

			       for(i = 1; i < data.length; ++i){
				       ret = ret + ' ' + escapeHtml(data[i].name);
			       }
			       return ret;
		      }
	    }
        ]
    });
});
</script>
@endsection
