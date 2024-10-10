<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{
    ConfiguracaoModulo,
    CadastroObra,
    NotificacoesCalenadrios,
    Notification
};

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {

            // Obter a data atual
            $currentDate = Carbon::now();


            // Calcular a data de dois dias atrás
            $doisDiasAtras = $currentDate->copy()->subDays(2);


            $id_obra = Session::get('obra')['id'] ?? null;

            if (!empty($id_obra)) {
                $obra = CadastroObra::find($id_obra);

                // Filtrar os eventos do mês vigente
                $eventos = NotificacoesCalenadrios::whereYear('end', $currentDate->year)
                    ->with('obra')
                    ->whereMonth('end', $currentDate->month)
                    ->where('id_obra', $id_obra)
                    ->get();
            } else {

                $obra = [
                    "codigo_obra" => "Todas as obras"
                ];

                // Filtrar os eventos do mês vigente
                $eventos = NotificacoesCalenadrios::whereYear('end', $currentDate->year)
                    ->whereMonth('end', $currentDate->month)
                    ->with('obra')
                    ->get();
            }

            $obra = (object) $obra;

            //listar as notificações
            $notificacoesNaoLidas = Notification::with("obra")
                ->where('id_obra', $id_obra)
                ->whereBetween('created_at', [$doisDiasAtras, $currentDate])
                ->orWhereNull('created_at')
                ->get();


            $view->with(
                [
                    'modulos_permitidos' => ConfiguracaoModulo::get_modulos_permitidos(),
                    'obras_lista' => CadastroObra::all(),
                    'notificacoes' => $notificacoesNaoLidas,
                    'eventos' => $eventos
                ]
            );
        });
    }
}
