<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\{
    Auth,
    Session,
    Log
};

class BloquearAcesso
{
    public function handle(Request $request, Closure $next)
    {
        
    // dd(auth()->user()->bloqueado);
        
        if (auth()->check() && auth()->user()->bloqueado) {
            
            auth()->logout(); // Desloga o usuário bloqueado
            return redirect()->route('bloqueado')->with('error', 'Aguarde a liberação do seu usuário no sistema.');
        }

        return $next($request);
    }
}