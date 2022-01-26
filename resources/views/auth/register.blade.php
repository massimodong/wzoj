@if (null !== request()->get('token'))
  @include ('auth.register_form', ["invitation" => \App\Invitation::where('token',request()->get('token'))->where('remaining' , '<>' , 0)->first()])
@else
  @include ('auth.choose_register_token', ["invitations" => \App\Invitation::where('private' , false)->where('remaining' , '<>' , 0)->get()])
@endif
