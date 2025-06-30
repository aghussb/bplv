<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Helpers\Helper;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    function buildTree(array $array, $parentId = 0)
    {
        $tree = [];
        foreach ($array as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['items'] = [];
                $children = $this->buildTree($array, $item['id']);

                if (!empty($children)) {
                    $item['items'] = $children;
                }

                $tree[] = $item;
            }
        }
        return $tree;
    }

    public function index()
    {
        $menus = $this->buildTree(Menu::where("layout", (request()->layout ?? "admin"))->orderBy('urutan', 'asc')->get()->toArray());
        // dd($menus);
        return inertia('Admin/Menus/Menu', [
            'menus' => $menus,
        ]);
    }

    public function checkValidation(Request $request)
    {
        // https://stackoverflow.com/questions/25187846/how-to-give-custom-field-name-in-laravel-form-validation-error-message
        // https://stackoverflow.com/questions/17047116/laravel-validation-attributes-nice-names
        $form = $request->form;

        $this->validate(
            $request,
            [
                'form.icon' => 'required',
                'form.label' => 'required',
                'form.is_permission' => 'required',
                'form.config' => 'required',
                // 'form.route_name' => 'nullable|sometimes|unique:menus,route_name'. (!empty($form['id']) ? ",".$form['id'] : ''),
            ],
            [
                'form.icon' => [ 'required' => 'Icon belum dipilih', ],
                'form.label' => [ 'required' => 'Label belum diisi', ],
                'form.is_permission' => [ 'required' => 'Hak Akses belum dipilih', ],
                'form.config' => [ 'required' => 'Jenis Config belum dipilih', ],
                // 'form.route_name' => [ 'unique' => 'Route Name sudah ada', ],
            ],
            []
        );

        if (!empty($form['route_name'])) {
            $statusAdaRoute = false;
            $allRoutes = Route::getRoutes();
            foreach ($allRoutes as $key => $value) {
                if ($value->getName() != "") {
                    if ($value->getName() == $form['route_name']) {
                        $statusAdaRoute = true;
                    }
                }
            }

            if (!empty($form['id'])) {
                if (Menu::where("route_name", $form['route_name'])->where("id", "!=", $form['id'])->exists()) {
                    throw ValidationException::withMessages(['form.route_name' => 'Route Name sudah ada']);
                }
            } else {
                if (Menu::where("route_name", $form['route_name'])->exists()) {
                    throw ValidationException::withMessages(['form.route_name' => 'Route Name sudah ada']);
                }
            }

            // if (!$statusAdaRoute) {
            //     throw ValidationException::withMessages(['form.route_name' => 'Route Name belum ditambahkan di web.php']);
            // }

        }
    
        return response()->json([
            'success' => true,
            'data' => "success"
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataObjectIdParentMenu = [];
            // dd($request->data);
            foreach ($request->data as $key => $value) {
                if (empty($value['deleted'])) {
                    $statusTransaction = true;
                    $objectMenu = [
                        "icon" => $value['icon'],
                        "label" => $value['label'],
                        "route_name" => $value['route_name'],
                        "urutan" => $key + 1,
                        "is_parent" => $value['is_parent'],
                        "layout" => $value['layout'],
                        "is_permission" => $value['is_permission'],
                        "config" => $value['config'],
                        "function" => $value['function'],
                    ];

                    if ($value['is_parent']) {
                        $objectMenu["parent_id"] = 0;
                        if (empty($value['id'])) {
                            $objectMenu['created_at'] = Carbon::now();
                            // $parentId = $key + 1;
                            $parentId = Menu::insertGetId($objectMenu);
                            DB::commit();

                            $statusTransaction = false;
                        } else {
                            $parentId = $value['id'];
                        }

                        $dataObjectIdParentMenu[$value['urutan']] = $parentId;
                    }

                    if ($value['urutan_parent']) {
                        $objectMenu["parent_id"] = $dataObjectIdParentMenu[$value['urutan_parent']];
                    } else {
                        $objectMenu["parent_id"] = 0;
                    }

                    if ($statusTransaction) {
                        if (empty($value['id'])) {
                            $objectMenu['created_at'] = Carbon::now();
                            Menu::insert($objectMenu);
                        } else {
                            Menu::find($value['id'])->update($objectMenu);
                        }

                        // Menu::updateOrCreate(['id' => ($value['id'] ?? null)], $objectMenu);
                        DB::commit();
                    } else {
                        Menu::find($parentId)->update($objectMenu);
                        DB::commit();
                    }
                } else {
                    Menu::where('id',$value['id'])->delete();
                    // DB::table("role_has_permissions")->whereIn("permission_id", Menu::find($value['id'])->permissions()->pluck("id"))->delete();
                    // Menu::find($value['id'])->permissions()->detach();
                    DB::commit();
                }
            }

            // dd($dataObjectIdParentMenu);
            return redirect()->route('menu', ['layout' => $value['layout']]);
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
