@if ($breadcrumbs)
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light">
    @foreach ($breadcrumbs as $breadcrumb)
      @if (!$breadcrumb->last)
        <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
      @else
        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->title }}</li>
      @endif
    @endforeach
    </ol>
  </nav>
@endif
