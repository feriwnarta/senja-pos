<?php

namespace App\Livewire\User;

use App\Models\PermissionCategory;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use function App\Models\paginate;

class Permission extends Component
{

    #[Rule('required|string|min:2|unique:roles,name')]
    public string $roleName;

    #[Rule('required|string|min:36')]
    public string $categoryPermission = '';

    #[Rule('required|string|min:2|unique:permissions,name')]
    public string $permissionName = '';

    #[Rule('required|string|min:2|unique:permission_categories,name')]
    public string $permissionCategoryName = '';


    public function mount()
    {

    }

    public function render()
    {
        $roles = Role::all();
        $permissions = \App\Models\Permission::with('category')
            ->join('permission_categories', 'permissions.permission_categories_id', '=', 'permission_categories.id')
            ->orderBy('permission_categories.name')
            ->orderBy('name')
            ->select('permissions.*')
            ->get();
        $permissionCategories = PermissionCategory::all();

        return view('livewire.user.permission', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissionCategories' => $permissionCategories,
        ]);
    }

    public function saveNewRole()
    {
        $this->validate([
            'roleName' => 'required|string|min:2|unique:roles,name'
        ]);

        try {
            // simpan
            DB::transaction(function () {
                $role = Role::create([
                    'name' => $this->roleName
                ]);

                $this->reset('roleName');
                notify()->success('sukses buat role');
            });
        } catch (Exception $exception) {
            Log::error('gagal membuat role baru');
            report($exception);
            notify()->error('Gagal');
        }

    }

    public function saveNewPermission()
    {
        $this->validate([
            'permissionName' => 'required|string|min:2|unique:permissions,name',
            'categoryPermission' => 'required|string|min:36'
        ]);

        try {
            // simpan
            DB::transaction(function () {
                \App\Models\Permission::create([
                    'name' => $this->permissionName,
                    'permission_categories_id' => $this->categoryPermission,
                ]);

                $this->reset('permissionName', 'categoryPermission');
                notify()->success('sukses buat permission');

            });
        } catch (Exception $exception) {
            Log::error('gagal membuat permission baru');
            report($exception);
            notify()->error('Gagal');
        }
    }

    public function saveNewPermissionCategory()
    {
        $this->validate([
            'permissionCategoryName' => 'required|string|min:2|unique:permission_categories,name'
        ]);

        try {
            // simpan
            DB::transaction(function () {
                PermissionCategory::create([
                    'name' => $this->permissionCategoryName,
                ]);

                $this->reset('permissionCategoryName');
                notify()->success('sukses buat category');

            });
        } catch (Exception $exception) {
            Log::error('gagal membuat permission category baru');
            report($exception);
            notify()->error('Gagal');
        }
    }
}
