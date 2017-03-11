@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<h1 class="text-center">FAQ:</h1>
<p id="what_is_wzoj">
  <h3>什么是{{ojoption('site_name')}}?</h3>
  {{ojoption('site_name')}}旨在提供一套简单易用的校内OJ系统,实现日常的训练、作业、比赛等功能。<br>
</p>
<p id="compile_options">
  <h3>我提交的程序是怎样运行的？</h3>
  后台运行的<a href="https://github.com/massimodong/wzoj-judger" target="blank">评测机</a>执行程序的编译、运行、评判任务。评测机以及提交的程序都在Linux环境中运行，部分语言的编译以及执行参数如下:
  <table border="1">
    <thead>
      <tr>
        <th style="width:70px"></th>
        <th style="width:700px"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>C</td>
        <td>gcc Main.c -o Main -fno-asm -Wall -lm --static -std=c99 -DONLINE_JUDGE</td>
      </tr>
      <tr>
        <td>C++</td>
	<td>g++ Main.cc -o Main -fno-asm -Wall -lm --static -std=c++0x -DONLINE_JUDGE</td>
      </tr>
        <td>Pascal</td>
	<td>fpc Main.pas -Cs32000000 -Sh -O2 -Co -Ct -Ci</td>
      <tr>
        <td>Python</td>
	<td>python3.* Main.py</td>
      </tr>
    </tbody>
  </table>
</p>
<p id="gravatar">
  <h3>如何自定义头像?</h3>
  你一定听说过<a href="http://cn.gravatar.com/" target="_blank">gravatar</a>(全球公认的头像).<br>
  用你的注册邮箱注册一个gravatar头像就好了。
</p>
@endsection
