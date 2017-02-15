@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="col-xs-9 row">
  @foreach ($home_page_problemsets as $problemset)
    <div class="col-xs-4">
      <div class="thumbnail problemset-dock" data-href="/s/{{$problemset->id}}">
        <div class="row">
          <div class="col-xs-12">
            <div style="padding: 9px;min-height: 130px;">
	      <small class="pull-right">{{trans('wzoj.problem_type_'.$problemset->type)}}</small>
              <div class="dock-heading">{{$problemset->name}}</div>
	      @if ($problemset->type <> 'set')
	      	{{$problemset->contest_start_at}} - <br>
		{{$problemset->contest_end_at}} <br>
	      @endif
  		{!! Purifier::clean($problemset->description) !!}
	    </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
<div class="col-xs-3 row">
  <div class="panel panel-wzoj">
    <div class="panel-heading">{{trans('wzoj.notices')}}</div>
    <div class="panel-body">
	{{trans('wzoj.none')}}
    </div>
  </div>
</div>
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	$(".problemset-dock").click(function() {
		window.document.location = $(this).data("href");
	});
});
</script>
@endsection
