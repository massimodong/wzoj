children: [
    @foreach ($tags as $tag)
    {
        innerHTML: "<div class='tags-chart-node' id='tag-{{$tag->id}}'>{{$tag->name}}</div>",
        collapsed: false,
        @if ($tag->child_tags->count() > 0)
            @include ('problem_tags_chart_recursive', ['tags' => $tag->child_tags()->orderBy('index', 'asc')->get()])
        @endif
    },
    @endforeach
]   
