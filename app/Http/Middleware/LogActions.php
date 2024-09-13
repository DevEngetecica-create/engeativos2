<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $action = $request->method() . ' ' . $request->path();
        $userEmail = Auth::check() ? Auth::user()->email : 'guest';
    
        Log::create([
            'action' => $action,
            'user_email' => $userEmail,
        ]);
    
        return $response;
    }
}
