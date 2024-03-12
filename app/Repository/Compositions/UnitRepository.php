<?php

namespace App\Repository\Compositions;

use App\Dto\UnitDTO;
use App\Models\Unit;

class UnitRepository implements \App\Contract\Compositions\UnitRepository
{

    public function create(UnitDTO $unitDTO): Unit
    {
        $unit = new Unit();
        $unit->code = $unitDTO->getCode();
        $unit->name = $unitDTO->getName();
        $unit->save();

        return $unit;
    }
}
