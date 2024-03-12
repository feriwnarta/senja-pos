<?php

namespace App\Repository\CentralKitchen;

use App\Dto\CentralKitchenDTO;
use App\Models\CentralKitchen;

class CentralKitchenRepository implements \App\Contract\CentralKitchen\CentralKitchenRepository
{

    public function show()
    {
        // TODO: Implement show() method.
    }

    public function create(CentralKitchenDTO $centralKitchenDTO): CentralKitchen
    {
        $centralKitchen = new CentralKitchen();
        $centralKitchen->code = $centralKitchenDTO->getCode();
        $centralKitchen->name = $centralKitchenDTO->getName();
        $centralKitchen->address = $centralKitchenDTO->getAddress();
        $centralKitchen->phone = $centralKitchenDTO->getPhone();
        $centralKitchen->email = $centralKitchenDTO->getEmail();
        $centralKitchen->save();

        return $centralKitchen;

    }
}
