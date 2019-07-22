@extends ('layouts.master')

@section ('title')
{{trans('wzoj.search_user')}}
@endsection

@section ('content')
  @foreach ($users as $user)
    <div class="card">
      <div class="row no-gutters">
        <div class="col-md-2">
          <a href="/users/{{$user->id}}"><img src="//cn.gravatar.com/avatar/{{md5(strtolower(trim($user->email)))}}?d=retro&s=128" class="card-img"></a>
        </div>
        <div class="col-md-10">
          <div class="card-body">
            <h4 class="card-title">{{$user->fullname}}</h4>
            <h6>{{$user->name}}</h6>
            <div>
              {{$user->description}}
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@section ('scripts')
<script>
var unit = $(".col-md-2:first-child").width();
$(".card-body").css("height", unit + "px");
$(window).resize(function(){
  var unit = $(".col-md-2:first-child").width();
  $(".card-body").css("height", unit + "px");
});
</script>
@endsection
