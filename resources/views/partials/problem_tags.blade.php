@foreach (Cache::tags(['problem_tags'])->rememberForever($problem->id, function() use($problem){
			return $problem->tags;
		}) as $tag)
    <a class="openpop" href="/tags-chart#tag-{{$tag->id}}"><span class="badge badge-secondary">{{$tag->name}}</span></a>
@endforeach
