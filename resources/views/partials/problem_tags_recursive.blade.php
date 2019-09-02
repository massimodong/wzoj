<ol class="dd-list">
  @foreach ($tags as $tag)
    <li class="dd-item dd3-item" data-id="{{$tag->id}}" id="tag-{{$tag->id}}">
      <div class="dd-handle dd3-handle"></div><div class="dd3-content">{{$tag->name}}</div>
      @if ($tag->child_tags->count() > 0)
        @include ('partials.problem_tags_recursive', ['tags' => $tag->child_tags()->orderBy('index', 'asc')->get()])
      @endif
    </li>
  @endforeach
</ol>
