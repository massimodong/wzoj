@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="row">
  <div class="col-xl-9 col-md-8">
  A
  </div>
  <div class="col-xl-3 col-md-4">
    <form>
      <div class="d-table-cell w-100">
        <input type="text" class="form-control" placeholder="{{trans('wzoj.search')}}">
      </div>
      <div class="d-table-cell align-middle">
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          AAA
          <input type="text">
        </div>
      </div>
    </form>
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
