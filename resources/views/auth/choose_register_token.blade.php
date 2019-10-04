@extends ('layouts.master')

@section ('title')
{{trans('wzoj.register')}}
@endsection

@section ('content')

<h1>{{trans('wzoj.available_tokens')}}:</h1>

<div class="row">
  <div class="col-12">
    @if (count($invitations) > 0)
      <div class="list-group">
      @foreach ($invitations as $invitation)
        <a href='/auth/register?token={{$invitation->token}}' class="list-group-item list-group-item-action">{{$invitation->description}}</a>
      @endforeach
      </div>
    @endif
  </div>

  <div class="col-12">
    <form action='/auth/register' method='get' class="form-inline">
      <label class="sr-only" for="token">{{trans('wzoj.token')}}</label>
      <input type='text' name='token' id='token' class="form-control my-2 mr-sm-2" placeholder="{{trans('wzoj.fill_your_own_token_here')}}" required>
      <button type="submit" class="btn btn-primary my-2 mr-sm-2">{{trans('wzoj.use_your_own_token')}}</button>
    </form>
  </div>
</div>

@endsection
