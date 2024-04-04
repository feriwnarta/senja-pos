<?php

namespace App\Livewire\User;

use App\Models\Role;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateRole extends Component
{

    #[Rule('array|min:0')]
    public Collection $permissions;
    #[Rule('required|string|min:2|unique:roles,name')]
    public string $roleName;

    public function mount()
    {
        $this->permissions = new Collection();
    }

    public function render()
    {
        return view('livewire.user.create-role');
    }

    public function handleSelectedPermission(string $id)
    {
        if (!$this->permissions->contains($id)) {
            $this->permissions->push($id);
            return;
        }

        // Cari posisi $id dalam koleksi dan hapusnya jika ditemukan
        $key = $this->permissions->search($id);
        if ($key !== false) {
            $this->permissions->forget($key);
        }

    }

    public function saveRole()
    {
        $this->validate();


        try {

            DB::transaction(function () {
                $role = Role::create([
                    'name' => $this->roleName
                ]);

                if ($this->permissions->isNotEmpty()) {
                    $role->givePermissionTo($this->permissions);
                }

                $this->reset();
                $this->redirect("/user/permission/create-role");
                notify()->success('berhasil buat role');
            });
        } catch (Exception $exception) {
            notify()->error('gagal membuat role baru');
            Log::error('gagal membuat role baru');
            report($exception);
        }
    }
}

