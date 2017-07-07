<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
        $schedule->exec('mysqldump -h '.env('DB_HOST').' -u '.env('DB_USERNAME').' -p'.env('DB_PASSWORD').' '.env('DB_DATABASE'))
                 ->daily()
                 ->before(function(){exec('mkdir -p '.base_path().'/storage/backup/database/');})
                 ->sendOutputTo(base_path().'/storage/backup/database/'.time().'.sql');
    }
}
