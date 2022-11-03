<?php

namespace App\Http\Middleware;

use App\Helpers\JwtAuth;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Este metodo comprueba si el usuario esta identificado
        $jwtauth = new JwtAuth();
        // $token que nos llega de la cabezera en un hedder de Angular
        $token = $request->header('token-usuario');
        // $token = $request->header('APP_KEY');

        // 1.- Comprobar si el Usuario esta identificado.
        $checkToken = $jwtauth->checkToken($token); // True si el token es correcto 
        // echo $checkToken;
        // die();
        if ($checkToken) {
            return $next($request);
        } else {
            $data = array(
                'status' => 'Error',
                'code' => 400,
                'message' => 'No tienes autorización para usar servicios de esta aplicación.'
            );
            return response()->json($data, $data['code']);
        }
        // Luego se configura para que funcione el middleware
    }
}
