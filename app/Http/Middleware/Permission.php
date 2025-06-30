<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Helpers\Helper;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $dataMenu = Inertia::getShared("sidebar_menus")[$authGuard->user()->roles[0]->layout];
        $getMenu = (new Helper())->searchNestedArray($dataMenu,"route_name",$request->route()->getName());
    
        foreach ($permissions as $permission) {
            if ($authGuard->user()->getPermissionArray()[($getMenu === false ? $permission : $getMenu['id'] . "." . $permission)] ?? false) {
                // if ($authGuard->user()->can(implode("_",explode(" ",strtolower($dataMenu[$getKeyMenu]['label']))).".".$permission)) { //by session
                // if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
