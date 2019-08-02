<nav aria-label="solutions pagination">
  <ul class="pagination">
    @if ($prev_id == -1)
      <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">{{trans('wzoj.prevpage')}}</a></li>
    @else
      <li class="page-item"><a class="page-link prev_page_url" href="#">{{trans('wzoj.prevpage')}}</a></li>
    @endif
    <li class="page-item"><a class="page-link top_page_url" href="#">{{trans('wzoj.toppage')}}</a></li>

    @if ($next_id == -1)
      <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">{{trans('wzoj.nextpage')}}</a></li>
    @else
      <li class="page-item"><a class="page-link next_page_url" href="#">{{trans('wzoj.nextpage')}}</a></li>
    @endif
  </ul>
</nav>
