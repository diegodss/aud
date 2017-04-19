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
        Commands\CompromisoAlertaAVencer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        // corriendo
        $schedule->command('compromiso:alerta')->daily();
        $schedule->command('compromiso:alerta_a_vencer')->daily();
        $schedule->command('compromiso:alerta_suscripcion')->daily();
    }

}
