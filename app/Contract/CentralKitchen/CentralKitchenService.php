<?php

namespace App\Contract\CentralKitchen;

use App\Dto\CentralKitchenDTO;
use App\Http\Resources\CentralKitchenResource;
use Illuminate\Http\JsonResponse;

interface CentralKitchenService
{

    public function create(?CentralKitchenDTO $centralKitchenDTO);

}
