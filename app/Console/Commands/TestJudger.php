<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Judger;

class TestJudger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'judger:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the judgers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function ensure($b){
      if(!$b){
        $this->error("Error");
        abort(500);
      }
    }

    public function check_testcase($judger, $client, $testcase){
      $this->info("checking testcase ".$testcase->srcfile);
        $args = new \WJudger\SimpleArgs();
        $args->setJudgerid(0);
        $args->setToken($judger->token);
        $args->setLanguage($testcase->language);
        $args->setCode(file_get_contents("tests/wjudger-testcases/".$testcase->srcfile));
        $args->setInput($testcase->input);

        list($reply, $status) = $client->Simple($args)->wait();
        print "status: ".$reply->getStatus()."\n";
        print "time: ".$reply->getTimeused()."\n";
        print "memory: ".$reply->getMemoryused()."\n";

        while($reply->getStatus() == \WJudger\JudgeStatus::BUSY){
          list($reply, $status) = $client->Simple($args)->wait();
        }

        print "status: ".$reply->getStatus()."\n";
        print "time: ".$reply->getTimeused()."\n";
        print "memory: ".$reply->getMemoryused()."\n";

        $this->info("output: ".$reply->getOutput());

        if($reply->getCompileError()){
            $this->info("CE: ".$reply->getCompileErrorMessage());
        }

        if($reply->getRuntimeError()){
            $this->info("RE: ".$reply->getRuntimeErrorMessage());
        }

        $this->ensure($reply->getStatus() == \WJudger\JudgeStatus::OK);

        switch($testcase->result){
          case "OK":
            $this->ensure(!$reply->getCompileError());
            $this->ensure(!$reply->getRuntimeError());
            $this->ensure($reply->getOutput() == $testcase->output);
            break;
          case "CE":
            $this->ensure($reply->getCompileError());
            break;
          case "RE":
            $this->ensure($reply->getRuntimeError());
            break;
          default:
            abort(500);
            break;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $testcases = json_decode(file_get_contents("tests/wjudger-testcases/testcases.json"));
      $judgers = Judger::where("ip_addr", "!=", "")->get();
      $this->info("You have configured ".$judgers->count()." judger(s)");
      foreach($judgers as $judger){
        $this->info("Testing judger ".$judger->name." (".$judger->ip_addr.")");

        $client = new \WJudger\WJudgerClient($judger->ip_addr.":9717", [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);

        if($client->waitForReady(1000000)){
          $this->info("Established connection to server ".$client->getTarget());
        }else{
          $this->error("Failed connecting to server ".$client->getTarget());
          continue;
        }

        foreach($testcases as $testcase){
          $this->check_testcase($judger, $client, $testcase);
        }
      }
      return 0;
    }
}
