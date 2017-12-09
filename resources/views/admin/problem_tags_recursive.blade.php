<ol class="dd-list">
  @foreach ($tags as $tag)
    <li class="dd-item dd3-item" data-id="{{$tag->id}}" id="tag-{{$tag->id}}">
      <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">{{$tag->name}}</div>
      @if ($tag->child_tags->count() > 0)
        @include ('admin.problem_tags_recursive', ['tags' => $tag->child_tags])
      @endif
    </li>
  @endforeach
</ol>
