<?php

namespace App\Console;

use App\Console\Commands\ArticlesImportCommand;
use App\Console\Commands\ImportUltrashopEntitiesCommand;
use App\Console\Commands\SitemapGenerateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ImportUltrashopEntitiesCommand::class)->hourly()->environments('production');

        $schedule->command(SitemapGenerateCommand::class)->daily();

        $schedule->command(ArticlesImportCommand::class)->onOneServer()->withoutOverlapping()->runInBackground()->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
