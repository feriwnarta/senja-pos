<?php

namespace Tests\Feature;

use App\Models\WarehouseItemReceipt;
use App\Models\WarehouseItemReceiptRef;
use Tests\TestCase;

class TestWarehouseReceiptMorph extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {

        $wh = WarehouseItemReceipt::create([
            'warehouses_id' => '9ad7d990-82d1-4254-ab82-306a20aba28a',
            'created_by' => '9ad7d990-82d1-4254-ab82-306a20aba28a',
        ]);


        $wh->reference()->save(new WarehouseItemReceiptRef());

    }
}
