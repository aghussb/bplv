<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Role;
use App\Helpers\Helper;
use App\Models\Permission;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use League\CommonMark\Extension\SmartPunct\EllipsesParser;

class RoleController extends Controller
{
    function buildTree(array $array, $parentId = 0)
    {
        $tree = [];
        foreach ($array as $item) {
            if ($item['parent_id'] == $parentId) {
                // $item['children'] = [];
                $children = $this->buildTree($array, $item['id']);

                if (!empty($children)) {
                    $item['children'] = $children;
                }

                $tree[] = $item;
            }
        }
        return $tree;
    }

    function addKeysToTree(&$array, $parentKey = '')
    {
        $keyCounter = 0;

        foreach ($array as &$item) {
            $item['key'] = $parentKey !== '' ? "$parentKey-$keyCounter" : "$keyCounter";
            $keyCounter++;

            if (isset($item['children']) && is_array($item['children'])) {
                $this->addKeysToTree($item['children'], $item['key']);
            }
        }
    }

    function extractKeysAsExpendedMenuRules($array)
    {
        $result = [];

        foreach ($array as $item) {
            if (isset($item['key'])) {
                $result[$item['key']] = true;
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $childrenResult = $this->extractKeysAsExpendedMenuRules($item['children']);
                $result = array_merge($result, $childrenResult);
            }
        }

        return $result;
    }

    function extractIDAsCheckedRules($array)
    {
        $result = [];

        foreach ($array as $item) {
            if (isset($item['key'])) {
                $result[] = $item['id'];
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $childrenResult = $this->extractIDAsCheckedRules($item['children']);
                $result = array_merge($result, $childrenResult);
            }
        }

        return $result;
    }

    // function extractKeysAsSelectedMenuRules($array)
    // {
    //     $result = [];

    //     foreach ($array as $item) {
    //         $object = [
    //             "checked" => false,
    //             "partialChecked" => false,
    //         ];
    //         if (isset($item['key'])) {
    //             if (!empty($item['children']) && count($item['children']) != 1) {
    //                 if (array_reduce($item['children'], function ($carry, $item) {
    //                     return $carry || (isset($item['checked']) && $item['checked'] === false);
    //                 }, false)) {
    //                     $object['partialChecked'] = true;
    //                 } else {
    //                     $object['checked'] = true;
    //                 }
    //             } else {
    //                 if ($item['checked']) {
    //                     $object['checked'] = true;
    //                 }
    //             }
    //             $result[$item['key']] = $object;
    //         }

    //         if (isset($item['children']) && is_array($item['children'])) {
    //             $childrenResult = $this->extractKeysAsSelectedMenuRules($item['children']);
    //             $result = array_merge($result, $childrenResult);
    //         }
    //     }

    //     return $result;
    // }

    function mergedMenus(array $arrays)
    {
        $merged = [];

        foreach ($arrays[0] as $item) {
            $combinedItem = $item;

            for ($i = 1; $i < count($arrays); $i++) {
                foreach ($arrays[$i] as $item2) {
                    if ($item2["id"] === $item["id"]) {
                        $combinedItem = array_merge($combinedItem, $item2);
                        break;
                    }
                }
            }

            $merged[] = $combinedItem;
        }

        usort($merged, function ($a, $b) {
            return $a['urutan'] - $b['urutan'];
        });
        return $merged;
    }

    public function index()
    {
        $roles = Role::where('name', 'like', '%' . request()->cari . '%')
            ->orderBy("id")->paginate(10);

        $roles->getCollection()->transform(function ($value) {
            $dataPermissions = Permission::whereHas("roles", function ($q) use ($value) {
                $q->where("id", $value->id);
            })
                ->get()
                ->toArray();

            $dataMenuRoles = Menu::select(["id", "label", "route_name", "parent_id", "urutan", "is_parent", "layout", "is_permission", "config", "function", "icon as iconMenu"])->where('is_permission', 1)
                ->whereHas("roles", function ($q) use ($value) {
                    $q->where("id", $value->id);
                })
                ->get();

            $dataTransformIDViewPermissions = [];
            $dataTransformMenuPermissions = [];
            $dataAdditionalRolePermissions = [];
            foreach ($dataPermissions as $permission) {
                $nameParts = explode(".", $permission['name']);
                if (count($nameParts) == 2 && $nameParts[1] == "view" && !empty($permission['menu_id'])) {
                    $dataTransformIDViewPermissions[] = (int)$nameParts[0];
                } else {
                    if (count($nameParts) == 2) {
                        $dataTransformMenuPermissions[$nameParts[0]][] = $nameParts[1];
                    } else {
                        $dataAdditionalRolePermissions[] = $nameParts[0];
                    }
                }
            }

            $dataMenuAll = Menu::select(["id", "label", "route_name", "parent_id", "urutan", "is_parent", "layout", "is_permission", "config", "function", "icon as iconMenu"])->where([
                "layout" => $value->layout,
                "is_permission" => 1,
            ])->orderBy('urutan', 'asc')->get()->toArray();

            foreach ($dataMenuRoles as $menus) {
                // $menus['checked'] = !empty($dataTransformViewPermissions[$menus['id']]) ? true : false;
                $menus['permissions'] = !empty($dataTransformMenuPermissions[$menus['id']]) ? $dataTransformMenuPermissions[$menus['id']] : [];
            }

            $dataMenu = $this->buildTree($this->mergedMenus([$dataMenuAll, $dataMenuRoles->toArray()]));
            $this->addKeysToTree($dataMenu);
            $dataExpandedMenu = $this->extractKeysAsExpendedMenuRules($dataMenu);

            $value->menu = $dataMenu;
            $value->expanded = $dataExpandedMenu;
            $value->permissions = $dataAdditionalRolePermissions;
            $value->checked = $dataTransformIDViewPermissions;
            $value->data_permissions = $dataPermissions;

            return $value;
        });
// dd($roles->toArray());
        $dataMenuAdmin = Menu::select(["id", "label", "route_name", "parent_id", "urutan", "is_parent", "layout", "is_permission", "config", "function", "icon as iconMenu"])->where([
            "layout" => "admin",
            "is_permission" => 1,
        ])->orderBy('urutan', 'asc')->get()->toArray();
        $dataMenuAdmin = $this->buildTree($dataMenuAdmin);
        $this->addKeysToTree($dataMenuAdmin);
        $dataExpandedAdmin = $this->extractKeysAsExpendedMenuRules($dataMenuAdmin);
        $dataCheckedAdmin = $this->extractIDAsCheckedRules($dataMenuAdmin);

        $dataMenuPublic = Menu::select(["id", "label", "route_name", "parent_id", "urutan", "is_parent", "layout", "is_permission", "config", "function", "icon as iconMenu"])->where([
            "layout" => "public",
            "is_permission" => 1,
        ])->orderBy('urutan', 'asc')->get()->toArray();
        $dataMenuPublic = $this->buildTree($dataMenuPublic);
        $this->addKeysToTree($dataMenuPublic);
        $dataExpandedPublic = $this->extractKeysAsExpendedMenuRules($dataMenuPublic);
        $dataCheckedPublic = $this->extractIDAsCheckedRules($dataMenuPublic);

        return inertia('Admin/Role/Role', [
            'roles' => $roles->toArray(),
            'menus' => [
                "admin" => [
                    "menu" => $dataMenuAdmin,
                    "expanded" => $dataExpandedAdmin,
                    "checked" => $dataCheckedAdmin,
                ],
                "public" => [
                    "menu" => $dataMenuPublic,
                    "expanded" => $dataExpandedPublic,
                    "checked" => $dataCheckedPublic,
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'form.name' => 'required|unique:roles,name' . (!empty($request->id) ? "," . $request->id : ''),
                'form.layout' => 'required',
            ],
            [
                'form.name' => ['required' => 'Role belum diisi'],
                'form.name' => ['unique' => 'Role sudah ada'],
                'form.layout' => ['required' => 'Menu Layout belum dipilih'],
            ],
            []
        );

        if (!count($request->form['checked'])) {
            throw ValidationException::withMessages(['form.checked' => 'Menu belum ada yang dipilih']);
        }

        DB::beginTransaction();
        try {
            if (!empty($request->id)) {
                $idRoles = $request->id;
                Role::find($idRoles)->update([
                    "name" => $request->form['name'],
                    "guard_name" => "web",
                    "layout" => $request->form['layout'],
                    "updated_at" => Carbon::now()
                ]);
            } else {
                $idRoles = Role::insertGetId([
                    "name" => $request->form['name'],
                    "guard_name" => "web",
                    "layout" => $request->form['layout'],
                    "created_at" => Carbon::now()
                ]);
            }

            Role::findOrFail($idRoles)->menus()->sync($request->form['checked']);

            $dataPermissions = [];
            foreach ($request->menu as $valueMenu) {
                if (!empty($valueMenu['permissions'])) {
                    foreach ($valueMenu['permissions'] as $valuePermission) {
                        $dataPermissionsInMenu = Permission::firstOrCreate(
                            ['name' => $valueMenu['id'] . "." . $valuePermission],
                            [
                                'name' => $valueMenu['id'] . "." . $valuePermission,
                                'guard_name' => "web",
                                'menu_id' => $valueMenu['id'],
                                'created_at' => Carbon::now()
                            ]
                        );
                        $dataPermissions[] = $dataPermissionsInMenu->id;
                    }
                }
            }

            foreach ($request->form['checked'] as $value) {
                $dataPermissionsView = Permission::firstOrCreate(
                    ['name' => $value . ".view"],
                    [
                        'name' => $value . ".view",
                        'guard_name' => "web",
                        'menu_id' => $value,
                        'created_at' => Carbon::now()
                    ]
                );
                $dataPermissions[] = $dataPermissionsView->id;
            }

            foreach ($request->form['permissions'] as $value) {
                $dataPermissionAdditional = Permission::firstOrCreate(
                    ['name' => $value],
                    [
                        'name' => $value,
                        'guard_name' => "web",
                        'created_at' => Carbon::now()
                    ]
                );
                $dataPermissions[] = $dataPermissionAdditional->id;
            }
            Role::findOrFail($idRoles)->permissions()->sync($dataPermissions);

            DB::commit();
            return redirect()->route('role');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            Role::findById(request()->id)->delete();
            DB::commit();
            return redirect()->route('role');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

}
