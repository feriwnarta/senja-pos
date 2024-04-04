<?php

namespace App\Contract\Compositions;

use App\Dto\UnitDTO;
use App\Models\Unit;

interface UnitService
{

    public function createUnit(UnitDTO $unitDTO): Unit;

}
