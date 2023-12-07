<?php

namespace App\Service;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Collection;

interface CompositionService
{

    public function findOutletById(string $id): ?Outlet;

    public function findCentralKitchenById(string $id): ?CentralKitchen;

    public function getAllUnit(): ?Collection;

    public function getAllCategory(): ?Collection;

    public function getPlacement(string $identifier, bool $isOutlet): ?array;

    public function saveItem(string $route, string $routeProduce, string $inStock, string $minimumStock, $thumbnail, bool $isOutlet, ?string $placement, string $code, string $name, string $unit, string $description, string $category, string $url, string $avgCost, string $lastCost): string;

}
