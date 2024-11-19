<?php

namespace App\Console;

use App\Jobs\SendDoseNotification;
use App\Models\Dose;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $doses = Dose::where('status', 'Aguardando')
                ->where('timestamp', '<=', now()) // Verifica doses no horário ou atrasadas
                ->get();

            foreach ($doses as $dose) {
                SendDoseNotification::dispatch($dose); // Envia o Job para a fila
            }
        })->everyMinute(); // Executa a cada minuto
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
