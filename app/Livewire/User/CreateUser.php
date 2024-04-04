<?php

namespace App\Livewire\User;

use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateUser extends Component
{

    #[Rule('required|string|min:2|unique:users,name')]
    public string $name;
    #[Rule('required|email|min:2|unique:users,email')]
    public string $email;
    #[Rule('required|string|min:5')]
    public string $password;
    #[Rule('required|string|min:36')]
    public string $role = '';

    public function render()
    {
        return view('livewire.user.create-user');
    }

    public function saveNewUser()
    {
        $this->validate();

        try {

            DB::transaction(function () {
                $user = \App\Models\User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password)
                ]);

                $role = Role::findOrFail($this->role);
                $user->assignRole($role);

                notify()->success('Berhasil buat user');
                $this->reset();

            });
        } catch (Exception $exception) {
            notify()->error('gagal buat user');
            Log::error('gagal membuat user baru');
            report($exception->getTraceAsString());
        }
    }
}
