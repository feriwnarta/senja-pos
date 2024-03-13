<?php

namespace App\Livewire\CentralKitchen;

use App\Dto\CentralKitchenDTO;
use App\Http\Requests\CentralKitchenRequest;
use App\Service\CentralKitchen\CentralKitchenService;
use App\Service\CentralKitchen\Interface\CentralKitchenRepository;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddCentralKitchen extends Component
{

    #[Rule('required|min:5|unique:central_kitchens,code')]
    public string $code;
    #[Rule('required|min:5')]
    public string $name;
    public string $address = '';
    public string $phone = '';
    public string $email = '';


    public function render()
    {
        return view('livewire.central-kitchen.add-central-kitchen');
    }

    public function save()
    {
        $this->validate();

        $repository = app()->make(\App\Repository\CentralKitchen\CentralKitchenRepository::class);
        $service = new CentralKitchenService($repository);
        $dto = new CentralKitchenDTO($this->code, $this->name);
        $dto->setAddress($this->address);

        $result = $service->create($dto);

        $this->reset();
        $this->redirect("/central-kitchen/list-central-kitchen/view/{$result->id}");
        notify()->success('Sukses buat central kitchen');

    }

}

