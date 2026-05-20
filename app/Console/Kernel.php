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

        // Sincronización de fichas Peru Compras: cada lunes a las 06:00 (hora Lima, UTC-5)
        // --crear               → crea productos para fichas sin match en BD
        // --suspender-sin-ficha → suspende productos de modelos PC sin codigo_pc
        $schedule->command('sync:fichas --crear --suspender-sin-ficha')
            ->weeklyOn(1, '11:00')  // 06:00 Lima = 11:00 UTC
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
