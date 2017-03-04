@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<h1 class="text-center">FAQ:</h1>
<p id="what_is_wzoj">
  <h3>什么是{{ojoption('site_name')}}?</h3>
  问老师去。
</p>
<p id="gravatar">
  <h3>如何自定义头像</h3>
  你一定听说过<a href="http://cn.gravatar.com/" target="_blank">gravatar</a>(全球通用头像系统).<br>
  用你的注册邮箱注册一个gravatar头像就好了。
</p>
@endsection
