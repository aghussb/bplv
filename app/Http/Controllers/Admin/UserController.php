<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('name', 'like', '%' . request()->cari . '%')
            ->orderBy("id")->paginate(10);

        $users->getCollection()->transform(function ($value) {
            
            $value->roles = collect($value->roles()->get())->map(function($valueEachRoles) {
                return $valueEachRoles['name'];
            });

            return $value;
        });

        // dd($users->toArray());
        return inertia('Admin/User/User', [
            'users' => $users->toArray(),
            'roles' => Role::get()->toArray()
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->form);
        $form = $request->form;

        $this->validate(
            $request,
            [
                'form.name' => 'required',
                'form.email' => 'required|unique:users,email' . (!empty($form['id']) ? "," . $form['id'] : ''),
                'form.roles' => 'required',
                'form.password' => 'required|confirmed'
            ],
            [
                'form.name' => ['required' => 'Nama belum diisi',],
                'form.email' => [
                    'required' => 'Email belum diisi',
                    'unique' => 'Email sudah ada',
                ],
                'form.roles' => ['required' => 'Roles belum dipilih',],
                'form.password' => [
                    'required' => 'Password belum diisi',
                    'confirmed' => 'Password dan Konfirmasi Password tidak sama',
                ],
            ],
            []
        );
        
        if (Role::whereIn("name",$form['roles'])->distinct("layout")->count() > 1) {
            throw ValidationException::withMessages(['form.roles' => 'Tidak bisa memilih Roles dengan layout berbeda']);
        }
        
        // dd($form);
        DB::beginTransaction();
        try {
            $form['password'] = bcrypt($form['password']);
            unset($form['password_confirmation'], $form['roles']);

            if (!empty($form['id'])) {
                $form['updated_at'] = Carbon::now();
            } else {
                $form['created_at'] = Carbon::now();
                $form['updated_at'] = null;
            }

            $user = User::updateOrCreate(['id' => $form['id'] ?? 0], $form);
            $user->syncRoles($request->form['roles']);

            DB::commit();
            return redirect()->route('user');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            User::find(request()->id)->delete();
            DB::commit();
            return redirect()->route('user');
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
}
