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
        Commands\SyncKenyaProducts::class,
        Commands\SyncFichasCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Sincronización legacy
        $schedule->command('kenya:sync-products')->dailyAt('02:00');

        // Sincronización de fichas Peru Compras: cada lunes a las 06:00
        // --crear   → crea nuevos productos para fichas que no tienen match en BD
        $schedule->command('sync:fichas --crear')
            ->weeklyOn(1, '06:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/sync-fichas.log'));
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
