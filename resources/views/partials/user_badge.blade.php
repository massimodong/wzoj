<div style="display: inline">
  <a class="user-badge badge badge-light" data-toggle="popover" data-uid="{{$user->id}}" data-nickname="{{$user->nickname}}" data-uname="{{$user->name}}" data-avatarurl="{{$user->avatar_url('md')}}" data-description="{{$user->description}}" href="/users/{{$user->id}}">{{$user->shortname(6)}}</a>
</div>
