@foreach (Cache::tags(['problem_tags'])->rememberForever($problem->id, function() use($problem){
			return $problem->tags;
		}) as $tag)
  @if ($tag->reference_url <> '')
    <a href="{{$tag->reference_url}}"><span class="label label-default">{{$tag->name}}</span></a>
  @else
    <span class="label label-default">{{$tag->name}}</span>
  @endif
@endforeach
