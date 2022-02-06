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

        $client = new \WJudger\WJudgerClient($judger->ip_addr, [
            'credentials' => \Grpc\ChannelCredentials::createInsecure(),
        ]);

        if($client->waitForReady(1000000)){
          $this->info("Established connection to server ".$client->getTarget());
        }else{
          $this->error("Failed connecting to server ".$client->getTarget());
          continue;
        }

        $args = new \WJudger\JudgeArgs();
        //TODO: prepare args

        $call = $client->Judge($args);
        $replies = $call->responses();

        foreach ($replies as $reply){
          $this->info("!");
          //TODO: check reply
        }

      }
      return 0;
    }
}
