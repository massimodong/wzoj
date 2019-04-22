<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Sidebar;
use App\DiyPage;

class InitSidebarsAndDiyPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $faq_content = '
            <h1 class="text-center" style="text-align: center;">FAQ</h1>
            <h3>什么是 WZMS ONLINE JUDGE?</h3>
            <p>WZMS ONLINE JUDGE 旨在提供一套简单易用的校内 OJ 系统, 实现日常的训练、作业、比赛等功能。</p>
            <h3>我提交的程序是怎样运行的?</h3>
            <p>后台运行的<a href="https://github.com/massimodong/wzoj-judger" target="blank">评测机</a>执行程序的编译、运行、评判任务。评测机以及提交的程序都在 Linux 环境中运行，部分语言的编译以及执行参数如下:</p>
            <table border="1">
                <tbody>
                    <tr>
                        <td style="padding: 0 6px;">C</td>
                        <td style="padding: 0 6px;"><code>gcc Main.c -o Main -fno-asm -Wall -lm --static -std=c99 -DONLINE_JUDGE</code></td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">C++</td>
                        <td style="padding: 0 6px;"><code>g++ Main.cc -o Main -fno-asm -Wall -lm --static -std=c++0x -DONLINE_JUDGE</code></td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">Pascal</td>
                        <td style="padding: 0 6px;"><code>fpc Main.pas -Cs32000000 -Sh -O2 -Co -Ct -Ci</code></td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">Python</td>
                        <td style="padding: 0 6px;"><code>python3.* Main.py</code></td>
                    </tr>
                </tbody>
            </table>
            <h3>如何自定义头像?</h3>
            <p>你一定听说过 <a href="http://cn.gravatar.com/" target="_blank" rel="noopener noreferrer">Gravatar</a> (全球公认的头像)。<br />用你的注册邮箱注册一个 Gravatar 头像就好了。</p>
            <h3>错误代码都是什么意思?</h3>
            <table border="1">
                <tbody>
                    <tr>
                        <td style="padding: 0 6px;">TLE (Time Limit Exceeded)</td>
                        <td style="padding: 0 6px;">程序运行的时间已经超出了这个题目的时间限制, 请检查有没有死循环, 或者需要设计更优秀的算法。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">MLE (Memory Limit Exceeded)</td>
                        <td style="padding: 0 6px;">程序运行的内存已经超出了这个题目的内存限制。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">OLE (Output Limit Exceeded)</td>
                        <td style="padding: 0 6px;">程序的输出已经超出了这个题目的输出限制。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">PLE (Problem rule Limit Exceeded)</td>
                        <td style="padding: 0 6px;">违反问题规则。提交的代码不符合题目的规则, 请确认提交的代码前面部分与问题中的预置代码一致。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">RE (Runtime Error)</td>
                        <td style="padding: 0 6px;">程序可以通过编译, 但是在运行过程中出错。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">CE (Compile Error)</td>
                        <td style="padding: 0 6px;">编译器不能编译你的程序。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">WA (Wrong Answer)</td>
                        <td style="padding: 0 6px;">某个测试数据的输出答案不正确。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">PE (Presentation Error)</td>
                        <td style="padding: 0 6px;">你的程序给出了正确的答案, 但你的输出格式不符合题目的要求, 请检查空格和换行符是否符合要求。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">WAIT</td>
                        <td style="padding: 0 6px;">你的程序正在等待测试, 请稍等再查看结果。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">TESTING</td>
                        <td style="padding: 0 6px;">系统正在测试你的程序。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">TESTED</td>
                        <td style="padding: 0 6px;">系统已经测试完你的程序。(OI 类型的竞赛特有)</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">JE (Judge Error)</td>
                        <td style="padding: 0 6px;">判题系统内部错误, 请联系管理员。</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 6px;">AC (Accepted)</td>
                        <td style="padding: 0 6px;">恭喜你, 答案完全正确。</td>
                    </tr>
                </tbody>
            </table>
        ';
        Sidebar::create(['name' => 'FAQ' ,'url' => '/faq']);
        DiyPage::create(['name' => 'FAQ' ,'url' => 'faq', 'content' => $faq_content]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
