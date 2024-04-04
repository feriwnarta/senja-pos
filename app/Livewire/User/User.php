<?php

namespace App\Livewire\User;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class User extends Component
{

    public Collection $users;

    public function mount()
    {

        $users = \App\Models\User::all();

        if ($users->isNotEmpty()) {
            $this->users = $users;
        }
    }

    public function render()
    {
        return view('livewire.user.user');
    }
}
