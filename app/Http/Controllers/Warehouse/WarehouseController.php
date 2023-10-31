<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Item;

class WarehouseController extends Controller
{

    public function getITem()
    {
        return Item::orderBy('id')->cursorPaginate(20);
    }
}
