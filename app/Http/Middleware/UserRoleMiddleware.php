<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {
        // $role = "user|admin|group-head";
        $roleArray = explode('|', $role);
        // dd($role, $rolesArray);

        // $userRoles = ['user', 'moderator', 'admin', 'super-admin'];
        $userRoles = [request()->user()?->role??'user'];
        // dd($userRoles);
        
        foreach($roleArray as $checkRole){
            if(in_array($checkRole, $userRoles)
            ){
                return $next($request);
            }
        }
        // return $next($request);

        $response['success'] = false;
        $response['message'] = 'Unauthorized: Access Denied, You do not have the necessary permission(s)';
        $statusCode = 401;

        return response()->json($response, $statusCode);
    }
}
