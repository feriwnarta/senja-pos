<?php

namespace App\Repository\CentralKitchen;

use App\Dto\CentralKitchenDTO;
use App\Http\Resources\CentralKitchenResource;
use App\Models\CentralKitchen;
use Illuminate\Support\Facades\Log;

class CentralKitchenRepository implements \App\Contract\CentralKitchen\CentralKitchenRepository
{

    public function show()
    {
        // TODO: Implement show() method.
    }

    public function create(CentralKitchenDTO $centralKitchenDTO): CentralKitchenResource
    {
        $centralKitchen = new CentralKitchen();
        $centralKitchen->code = $centralKitchenDTO->getCode();
        $centralKitchen->name = $centralKitchenDTO->getName();
        $centralKitchen->address = $centralKitchenDTO->getAddress();
        $centralKitchen->phone = $centralKitchenDTO->getPhone();
        $centralKitchen->email = $centralKitchenDTO->getEmail();
        $centralKitchen->save();

        return new CentralKitchenResource($centralKitchen);

    }
}
