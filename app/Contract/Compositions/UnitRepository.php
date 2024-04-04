<?php

namespace App\Contract\Compositions;

use App\Dto\UnitDTO;
use App\Models\Unit;

interface UnitRepository
{

    public function create(UnitDTO $unitDTO): Unit;

}
