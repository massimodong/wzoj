@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('home') !!}
<div class="row">
  <div class="col-xl-9 col-md-8">
  A
  </div>
  <div class="col-xl-3 col-md-4">
    <div class="overflow-auto" id="home_sidebar">
    @foreach ($sidePanels as $sidePanel)
      <div class="py-1"><div class="card">
        <div class="card-body">
          <h5 class="card-title">{{$sidePanel->title}}</h5>
          <p class="card-text">{!!$sidePanel->content!!}</p>
        </div>
      </div></div>
    @endforeach
    </div>
  </div>
</div>
@endsection
