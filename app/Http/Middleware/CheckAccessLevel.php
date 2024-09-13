<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $level)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
    
        Log::info('Middleware CheckAccessLevel executado');

        // Verificar o nível de acesso do usuário
        $userLevel = session('usuario_vinculo')->id_nivel;


        // Comparar o nível de acesso do usuário com o nível permitido
        if ($userLevel == $level) {
            // Redirecionar ou retornar uma resposta de erro, caso o acesso seja negado
            
              $notification = array(
                'title' => "Atenção!",
                'message' => "Acesso negado!!!",
                'type' => 'danger'
            );
            
            return redirect()->back()->with($notification);
        }

        return $next($request);
    }
}
