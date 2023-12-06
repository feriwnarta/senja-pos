<?php

namespace App\Service;

use App\Models\CentralKitchen;
use App\Models\Outlet;

interface CompositionService
{

    public function findOutletById(string $id): ?Outlet;

    public function findCentralKitchenById(string $id): ?CentralKitchen;

}
