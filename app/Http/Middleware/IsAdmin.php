<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     $user = auth('api')->user();
    //     if ($user && $user->role == 'admin') {
    //         return $next($request);
    //     } else {
    //         return response()->json([
    //             'status' => 403,
    //             'msg' => 'Valida de nuevo',
    //             'error' => 'Usted no tiene credenciales de Administrador'
    //         ], 403);
    //     }
    // }
    
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        return response()->json([
            'status' => 403,
            'msg' => 'Valida de nuevo',
            'error' => 'Usted no tiene credenciales de Administrador'
        ], 403);
    }
}
