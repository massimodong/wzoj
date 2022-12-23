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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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

        $args = new \WJudger\SimpleArgs();
        $args->setToken("123456");
        $args->setLanguage(1);
        $args->setCode("#include<iostream>\n#include<unistd.h>\nint main(){\nsleep(1);\nstd::cerr<<1<<std::endl;\nint a, b;\nstd::cin>>a>>b;\na=*((int *)0);\nstd::cout<<a+c<<std::endl;\n;\n}\n");
        $args->setInput("4 5\n");
        $this->info($args->getCode());

        list($reply, $status) = $client->Simple($args)->wait();
        print "time: ".$reply->getTimeused()."\n";
        print "memory: ".$reply->getMemoryused()."\n";
        if($reply->getCompileError()){
          print "ce: ".$reply->getCompileErrorMessage()."\n";
        }else if($reply->getRuntimeError()){
          print "re: ".$reply->getRuntimeErrorMessage()."\n";
        }else{
          print "output: ".$reply->getOutput()."\n";
        }


      }
      return 0;
    }
}
