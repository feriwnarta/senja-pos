<?php

namespace App\Livewire;

use Livewire\Component;

class UserAccount extends Component
{
    public $index = 0;
    public $dynamicData = [];

    public function addInput()
    {
        $this->index++;
        $this->dynamicData[] = ['', '', ''];
    }

    public function addTwoInputs()
    {
        $this->dynamicData[0]['data'] = [''];
    }

    public function removeInput($index)
    {
        unset($this->dynamicData[$index]);
        $this->dynamicData = array_values($this->dynamicData);
    }

    public function printData()
    {
        // Metode untuk mencetak atau melakukan tindakan lain pada data yang sudah ditambahkan
        dump($this->dynamicData);
    }

    public function render()
    {

        return view('livewire.user-account');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name.0' => 'required',
            'email.0' => 'required',
            'name.*' => 'required',
            'email.*' => 'required|email',
        ],
            [
                'name.0.required' => 'name field is required',
                'email.0.required' => 'email field is required',
                'email.0.email' => 'The email must be a valid email address.',
                'name.*.required' => 'name field is required',
                'email.*.required' => 'email field is required',
                'email.*.email' => 'The email must be a valid email address.',
            ]
        );

        foreach ($this->name as $key => $value) {
            Employee::create(['name' => $this->name[$key], 'email' => $this->email[$key]]);
        }

        $this->inputs = [];

        $this->resetInputFields();

        session()->flash('message', 'Employee Has Been Created Successfully.');
    }
}
