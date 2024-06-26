<?php

namespace App\Contract\CentralKitchen;

use App\Dto\CentralKitchenDTO;
use App\Models\CentralKitchen;

interface CentralKitchenRepository
{
    public function show();

    public function create(CentralKitchenDTO $centralKitchenDTO): CentralKitchen;
}
