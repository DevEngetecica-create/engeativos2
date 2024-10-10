<?php

namespace App\Console;

use App\Http\Controllers\VeiculosDocsTecnicosController;
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
        // $schedule->command('inspire')->hourly();

       /*  $schedule->call(function () {
            // Chama o método para verificar documentos e enviar e-mails
            app(VeiculosDocsTecnicosController::class)->verificarDocumentos();
        })->everyMinute(); */

        // Agendar para executar no dia 1º de cada mês às 08:00
        $schedule->command('email:send-monthly-event-report')
                 ->monthlyOn(1, '08:00') // Dia 1
                 ->timezone('America/Sao_Paulo');

        // Agendar para executar no dia 2 de cada mês às 08:00
        $schedule->command('email:send-monthly-event-report')
                 ->monthlyOn(2, '08:00') // Dia 2
                 ->timezone('America/Sao_Paulo');
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

    protected $commands = [
       // \App\Console\Commands\VerificarDocumentosCommand::class,
        Commands\SendMonthlyEventReport::class,
    ];
}
