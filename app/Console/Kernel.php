<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\CompromisoAlerta::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // $schedule->command('inspire')
        //          ->hourly();
//        $schedule->command('compromiso:alerta')->dailyAt('17:06')(); //daily();
        //$schedule->command('compromiso:alerta')->everyMinute();	
		$schedule->command('compromiso:alerta')->daily();		

        /*
          $schedule->call(function () {

          })->hourly(); //daily(); */
    }

}
