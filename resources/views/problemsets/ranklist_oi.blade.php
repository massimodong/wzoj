@extends ('layouts.master')

@section ('title')
{{trans('wzoj.ranklist')}}
@endsection

@section ('content')

@include ('layouts.ranktable')

@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	//initiate table
	$('#rank-table').isotope({
		getSortData: {
			id: '[id]',
			score: '.rank-score parseInt'
		},
		sortBy: ['score', 'id']
	});
});
</script>
@endsection
