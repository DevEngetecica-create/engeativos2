<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{
    ConfiguracaoModulo,
    CadastroObra,
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
        view()->composer('*', function($view) {
        
            $id_obra = Session::get('obra')['id'] ?? null;
        
            if (!empty($id_obra)) {
                $obra = CadastroObra::find($id_obra);
            } else {
                $obra = [
                    "codigo_obra" => "Todas as obras"
                ];
            }
        
            $obra = (object) $obra;
            
            // Obter a data atual
            $hoje = Carbon::now();
            
            // Calcular a data de dois dias atrás
            $doisDiasAtras = $hoje->copy()->subDays(2);
        
            $notificacoesNaoLidas = Notification::with("obra")
                                    ->where('id_obra', $id_obra)
                                    ->whereBetween('created_at', [$doisDiasAtras, $hoje])
                                    ->orWhereNull('created_at')
                                    ->get();
    
            $view->with(
                [
                    'modulos_permitidos' => ConfiguracaoModulo::get_modulos_permitidos(),
                    'obras_lista' => CadastroObra::all(),
                    'notificacoes' => $notificacoesNaoLidas
                ]
            );
        });
    }

}





































