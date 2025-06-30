<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Models\Menu;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        // dd((new Ziggy())->toArray());
        // dd(Menu::where("route_name",$request->route()->getName())->first());

        $dataMenu = [
            'admin' => $request->user() ? (new Helper())->buildTree((new Helper())->mergedMenus(array_merge(Menu::where("layout", "admin")->whereIn("config", ["Selalu Tampil"])->orderBy('urutan', 'asc')->get()->toArray(), ...Auth::user()->roles()->with(array('menus' => function ($query) {
                $query->where("layout", "admin")->whereIn("config", ["Selalu Tampil", "Setelah Login"])->orderBy('urutan', 'asc');
            }))->get()->pluck("menus")->toArray())),false) : (new Helper())->buildTree(Menu::where("layout", "admin")->whereIn("config", ["Selalu Tampil", "Sebelum Login"])->orderBy('urutan', 'asc')->get()->toArray(),false),

            'public' => $request->user() ? (new Helper())->buildTree((new Helper())->mergedMenus(array_merge(Menu::where("layout", "public")->whereIn("config", ["Selalu Tampil"])->orderBy('urutan', 'asc')->get()->toArray(), ...Auth::user()->roles()->with(array('menus' => function ($query) {
                $query->where("layout", "public")->whereIn("config", ["Selalu Tampil", "Setelah Login"])->orderBy('urutan', 'asc');
            }))->get()->pluck("menus")->toArray())),true) : (new Helper())->buildTree(Menu::where("layout", "public")->whereIn("config", ["Selalu Tampil", "Sebelum Login"])->orderBy('urutan', 'asc')->get()->toArray(),true),
        ];
        
        $dataPermissions = [];
        
        if ($request->user()) {
            $getMenu = (new Helper())->searchNestedArray($dataMenu, "route_name", $request->route()->getName());
            $dataPermissions = $request->user()->getPermissionArray();
            foreach ($dataPermissions as $key => $value) {
                $explodeKey = explode(".", $key);
                if (count($explodeKey) == 2) {
                    if (!empty($getMenu) && $getMenu['id'] == $explodeKey[0]) {
                        $dataPermissions[$explodeKey[1]] = true;
                        unset($dataPermissions[$key]);
                    }
                }
            }
        }

        // $dataMenus = Menu::where("route_name", $request->route()->getName());
        // if ($dataMenus->exists()) {
        //     $menusId = $dataMenus->first()->id;
        // }
        // else{
        //     $menusId = Menu::where("route_name", explode("/",request()->route()->getPrefix())[1])->first()->id;
        // }

        return array_merge(parent::share($request), [
            'session' => [
                'status' => fn () => $request->session()->get('status'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'auth' => [
                'user' => $request->user() ? $request->user() : null,
                'permissions' => $dataPermissions,
            ],
            'sidebar_menus' => $dataMenu,
            'route' => [
                // 'params' => $request->route()->parameters(),
                // 'query' => $request->all(),
                'path' => "/" . $request->path()
            ],
            'title' => Menu::where("route_name", $request->route()->getName())->first()->label ?? null,
            'menu_active' => empty(explode("/",request()->route()->getPrefix())[1]) ? null : (Menu::where("route_name", explode("/",request()->route()->getPrefix())[1])->first() ?? null),
            // 'ziggy' => fn () => (new Ziggy())->toArray()
        ]);
    }
}
