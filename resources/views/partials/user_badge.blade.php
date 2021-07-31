@if ((!isset($public)) || $public)
  @include ('partials.user_badge_nickname')
@else
  @include ('partials.user_badge_fullname')
@endif
