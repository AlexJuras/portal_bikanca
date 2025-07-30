<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login');
        }

        // Verificar se o usuário tem permissão de admin (tipo admin)
        if (Auth::user()->tipo !== 'admin') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
        }

        return $next($request);
    }
}
