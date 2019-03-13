<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Ifsnop\Mysqldump as IMysqldump;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
            try {
                $dump = new IMysqldump\Mysqldump('mysql:host=localhost;dbname=db_system', 'root', '');
                $dump->start(public_path().'\uploads\db_backup\db_system_'.date('Ymd-His').'.sql');
            } catch (\Exception $e) {
                echo 'mysqldump-php error: ' . $e->getMessage();
            }
        // })->everyMinute();
        })->saturdays()->at('10:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
