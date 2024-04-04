<?php

namespace Tests\Feature;

use App\Models\CentralKitchen;
use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Models\Warehouse;
use App\Models\WarehouseOutbound;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestCentralProductionService extends TestCase
{

    private CentralProductionService $centralService;

    public function testGenerateCode()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();

        $result = $this->centralService->generateCode($req->id, $central->id);
        print_r($result);
        self::assertNotNull($result);
        self::assertIsArray($result);

        self::assertEquals('PRDCENTRALPORIS01202312211', $result['code']);
    }

    public function testGenerateCodeReqStockNotFound()
    {

        $central = CentralKitchen::first();
        $this->expectException(ModelNotFoundException::class);
        $result = $this->centralService->generateCode('asd', $central->id);
    }

    public function testGenerateCodeReqNextMonth()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();

        $result = CentralProduction::create([
            'request_stocks_id' => $req->id,
            'code' => 'test',
            'increment' => '1',
            'central_kitchens_id' => $central->id

        ]);

        $this->expectsDatabaseQueryCount(5);
        // Atur waktu saat ini ke 1 Januari 2024 untuk mensimulasikan pergantian bulan
        Carbon::setTestNow(Carbon::create(2024, 1, 1));
        $code = $this->centralService->generateCode($req->id, $central->id);
        print_r($code);

        assertNotNull($code);
    }

    public function testGenerateCodeIfExistData()
    {

        $req = RequestStock::first();

        $central = CentralKitchen::first();
        $result = CentralProduction::create([
            'request_stocks_id' => $req->id,
            'code' => 'test',
            'increment' => '1',
            'central_kitchens_id' => $central->id

        ]);

        $this->expectsDatabaseQueryCount(5);
        // Atur waktu saat ini ke 1 Januari 2024 untuk mensimulasikan pergantian bulan
        $code = $this->centralService->generateCode($req->id);
        print_r($code);

        assertNotNull($code);
        self::assertEquals('PRDCENTRALPORIS01202312212', $code['code']);
    }

    public function testSaveProduction()
    {

        $req = RequestStock::first();
        $central = CentralKitchen::first();
        $result = $this->centralService->createProduction($req->id, $central->id);

        assertNotNull($result);
        self::assertIsString($result);
    }

    public function testGetReqStock()
    {

        $result = RequestStock::findOrFail('9ae48c86-9620-45c4-9f74-b90ecae71f84')->warehouse->centralKitchen->first()->id;

        Log::info($result);

        print_r($result);
        assertNotNull($result);

    }

    public function testSaveComponentRequest()
    {

        $component = [
            [
                'item' => [
                    'id' => '9ae48c16-be27-4dca-a209-85f6339f82fb',
                    'name' => 'Ayam giling',
                ],
                'recipe' => [
                    [
                        'isChecked' => true,
                        'id' => '9ae48c6a-0801-4c31-8b11-e446e9d27b98',
                        'item_component_id' => '9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3',
                        'item_component_name' => 'Paha ayam',
                        'item_component_unit' => 'GR',
                        'item_component_usage' => '150.00',
                        'qty_request' => 0,
                    ],
                ],
            ],
            [
                'item' => [
                    'id' => '9ad7dca8-15c8-4f58-8ab8-c696191328dd',
                    'name' => 'Bawang goreng',
                ],
                'recipe' => [
                    [
                        'isChecked' => true,
                        'id' => '9ad7dd55-7e48-4d31-85f2-4f3fb0e997db',
                        'item_component_id' => '9ad7dd21-4cc8-4554-97a8-8fc06ebdc689',
                        'item_component_name' => 'Garam',
                        'item_component_unit' => 'KG',
                        'item_component_usage' => '10.00',
                        'qty_request' => 0,
                    ],
                ],
            ],
        ];

        $prodId = CentralProduction::first()->id;

        $rs = $this->centralService->saveComponent($prodId, $component);
        assertNotNull($rs);
        self::assertTrue($rs);
        print_r($rs);


    }

    public function testJoinSameItemRequestMaterial()
    {

        $data = [
            "9ad7dca8-15c8-4f58-8ab8-c696191328dd" => [
                "targetItem" => [
                    "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                    "name" => "Bawang goreng"
                ],
                "components" => [
                    [
                        "id" => "9ad7dd21-4cc8-4554-97a8-8fc06ebdc689",
                        "name" => "Garam",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "72.00",
                        "unit" => "GR"
                    ]
                ]
            ],
            "9ae48c16-be27-4dca-a209-85f6339f82fb" => [
                "targetItem" => [
                    "id" => "9ae48c16-be27-4dca-a209-85f6339f82fb",
                    "name" => "Ayam giling"
                ],
                "components" => [
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "200.00",
                        "unit" => "GR"
                    ],
                    [
                        "id" => "9ad7dc13-6cc7-42ee-af14-6040aa775d6c",
                        "name" => "Telur",
                        "target_qty" => "320.00",
                        "unit" => "Butir"
                    ]
                ]
            ],
            "9ae6955d-e3ca-4dcc-9a07-80f48677e93f" => [
                "targetItem" => [
                    "id" => "9ae6955d-e3ca-4dcc-9a07-80f48677e93f",
                    "name" => "Chasio"
                ],
                "components" => [
                    [
                        "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                        "name" => "Bawang goreng",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ]
                ]
            ]
        ];

        $result = $this->centralService->joinSameItemRequestMaterial($data);
        self::assertIsArray($result);
        print_r($result);
        self::assertEquals(272.0, $result[1]['qty']);
    }

    public function testJoinSameItemMaterialNoSameItem()
    {

        $data = [
            "9ad7dca8-15c8-4f58-8ab8-c696191328dd" => [
                "targetItem" => [
                    "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                    "name" => "Bawang goreng"
                ],
                "components" => [
                    [
                        "id" => "9ad7dd21-4cc8-4554-97a8-8fc06ebdc689",
                        "name" => "Garam",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ],
                ]
            ],
            "9ae48c16-be27-4dca-a209-85f6339f82fb" => [
                "targetItem" => [
                    "id" => "9ae48c16-be27-4dca-a209-85f6339f82fb",
                    "name" => "Ayam giling"
                ],
                "components" => [
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "200.00",
                        "unit" => "GR"
                    ],
                    [
                        "id" => "9ad7dc13-6cc7-42ee-af14-6040aa775d6c",
                        "name" => "Telur",
                        "target_qty" => "320.00",
                        "unit" => "Butir"
                    ]
                ]
            ],
            "9ae6955d-e3ca-4dcc-9a07-80f48677e93f" => [
                "targetItem" => [
                    "id" => "9ae6955d-e3ca-4dcc-9a07-80f48677e93f",
                    "name" => "Chasio"
                ],
                "components" => [
                    [
                        "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                        "name" => "Bawang goreng",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ]
                ]
            ]
        ];

        $result = $this->centralService->joinSameItemRequestMaterial($data);
        self::assertIsArray($result);
        self::assertNotEquals(272.0, $result[1]['qty']);
        self::assertEquals(200.0, $result[1]['qty']);
    }

    public function testJoinSameItemRequestMaterialSameItem()
    {

        $data = [
            "9ad7dca8-15c8-4f58-8ab8-c696191328dd" => [
                "targetItem" => [
                    "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                    "name" => "Bawang goreng"
                ],
                "components" => [
                    [
                        "id" => "9ad7dd21-4cc8-4554-97a8-8fc06ebdc689",
                        "name" => "Garam",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ],
                ]
            ],
            "9ae48c16-be27-4dca-a209-85f6339f82fb" => [
                "targetItem" => [
                    "id" => "9ae48c16-be27-4dca-a209-85f6339f82fb",
                    "name" => "Ayam giling"
                ],
                "components" => [
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "200.00",
                        "unit" => "GR"
                    ],
                    [
                        "id" => "9ad7dc13-6cc7-42ee-af14-6040aa775d6c",
                        "name" => "Telur",
                        "target_qty" => "320.00",
                        "unit" => "Butir"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ]
                ]
            ],
            "9ae6955d-e3ca-4dcc-9a07-80f48677e93f" => [
                "targetItem" => [
                    "id" => "9ae6955d-e3ca-4dcc-9a07-80f48677e93f",
                    "name" => "Chasio"
                ],
                "components" => [
                    [
                        "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                        "name" => "Bawang goreng",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ]
                ]
            ]
        ];

        $result = $this->centralService->joinSameItemRequestMaterial($data);
        self::assertIsArray($result);
        self::assertNotEquals(25.00, $result[2]['qty']);

    }

    public function testGenerateCodeItemOut()
    {

        $warehouse = Warehouse::first();
        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);

        assertNotNull($rs);
        self::assertIsArray($rs);
        print_r($rs);

    }

    public function testGenerateCodeItemOutNextNumber()
    {

        $warehouse = Warehouse::first();
        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);

        $central = CentralProduction::first();

        assertNotNull($rs);
        self::assertIsArray($rs);
        print_r($rs);

        $outbound = WarehouseOutbound::create([
            'warehouses_id' => $warehouse->id,
            'central_productions_id' => $central->id,
            'code' => $rs['code'],
            'increment' => $rs['increment'],
        ]);

        // next number
        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);

        self::assertIsArray($rs);
        self::assertEquals(2, $rs['increment']);
        self::assertEquals('ITEMOUTGDG01202312262', $rs['code']);
    }

    public function testGenerateCodeItemOutNextMonth()
    {

        $warehouse = Warehouse::first();
        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);

        $central = CentralProduction::first();

        assertNotNull($rs);
        self::assertIsArray($rs);
        print_r($rs);

        $outbound = WarehouseOutbound::create([
            'warehouses_id' => $warehouse->id,
            'central_productions_id' => $central->id,
            'code' => $rs['code'],
            'increment' => $rs['increment'],
        ]);

        Carbon::setTestNow(Carbon::create(2024, 1, 1));


        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);
        assertNotNull($rs);
        self::assertIsArray($rs);
        self::assertEquals('ITEMOUTGDG01202401011', $rs['code']);
        self::assertEquals(1, $rs['increment']);
        print_r($rs);
    }


    public function testGenerateCodeItemOutDifferentWh()
    {

        $warehouse = Warehouse::latest()->first();
        $rs = $this->centralService->genereateCodeItemOut($warehouse->id);

        self::assertIsArray($rs);

        print_r($rs);


    }

    public function testFinishProduction()
    {

        $data = [
            '9af085c1-59e4-4390-b42d-90e3fe417b96' => [
                'result_id' => '9af41805-cb52-4923-9d63-74626ff4e2b5',
                'id' => '9af085c1-59e4-4390-b42d-90e3fe417b96',
                'name' => 'Mie',
                'target_qty' => '5.00',
                'unit' => 'KG',
                'result_qty' => 5.00,
            ],
        ];

        $productionId = '9af417f8-8937-43b1-88ef-9923eeff15c8';
        $note = '';

        $result = $this->centralService->finishProduction($data, $productionId, $note);

        assertNotNull($result);
    }


    public function testSaveRequest()
    {

        $data = [
            "9ad7dca8-15c8-4f58-8ab8-c696191328dd" => [
                "targetItem" => [
                    "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                    "name" => "Bawang goreng"
                ],
                "components" => [
                    [
                        "id" => "9ad7dd21-4cc8-4554-97a8-8fc06ebdc689",
                        "name" => "Garam",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "72.00",
                        "unit" => "GR"
                    ]
                ]
            ],
            "9ae48c16-be27-4dca-a209-85f6339f82fb" => [
                "targetItem" => [
                    "id" => "9ae48c16-be27-4dca-a209-85f6339f82fb",
                    "name" => "Ayam giling"
                ],
                "components" => [
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "200.00",
                        "unit" => "GR"
                    ],
                    [
                        "id" => "9ad7dc13-6cc7-42ee-af14-6040aa775d6c",
                        "name" => "Telur",
                        "target_qty" => "320.00",
                        "unit" => "Butir"
                    ]
                ]
            ],
            "9ae6955d-e3ca-4dcc-9a07-80f48677e93f" => [
                "targetItem" => [
                    "id" => "9ae6955d-e3ca-4dcc-9a07-80f48677e93f",
                    "name" => "Chasio"
                ],
                "components" => [
                    [
                        "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                        "name" => "Bawang goreng",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ]
                ]
            ]
        ];

        $wh = Warehouse::first();
        $prod = CentralProduction::first();

        $rs = $this->centralService->requestMaterialToWarehouse(
            $data, $wh->id, $prod->id
        );

        assertNotNull($rs);

    }

    public function testSaveRequestComponentEmpty()
    {

        $data = [
            "9ad7dca8-15c8-4f58-8ab8-c696191328dd" => [
                "targetItem" => [
                    "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                    "name" => "Bawang goreng"
                ],
                "components" => [
                    [
                        "id" => "9ad7dd21-4cc8-4554-97a8-8fc06ebdc689",
                        "name" => "Garam",
                        "target_qty" => "5.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "72.00",
                        "unit" => "GR"
                    ]
                ]
            ],
            "9ae48c16-be27-4dca-a209-85f6339f82fb" => [
                "targetItem" => [
                    "id" => "9ae48c16-be27-4dca-a209-85f6339f82fb",
                    "name" => "Ayam giling"
                ],
                "components" => [
                    [
                        "id" => "9ad7dbdd-b4b2-46e6-85e6-2c9ff19551b3",
                        "name" => "Paha ayam",
                        "target_qty" => "200.00",
                        "unit" => "GR"
                    ],
                    [
                        "id" => "9ad7dc13-6cc7-42ee-af14-6040aa775d6c",
                        "name" => "Telur",
                        "target_qty" => "320.00",
                        "unit" => "Butir"
                    ]
                ]
            ],
            "9ae6955d-e3ca-4dcc-9a07-80f48677e93f" => [
                "targetItem" => [
                    "id" => "9ae6955d-e3ca-4dcc-9a07-80f48677e93f",
                    "name" => "Chasio"
                ],
                "components" => [
                    [
                        "id" => "9ad7dca8-15c8-4f58-8ab8-c696191328dd",
                        "name" => "Bawang goreng",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ],
                    [
                        "id" => "9ad7dcfe-cde3-4caf-8e6a-0b545940853b",
                        "name" => "Bawang merah",
                        "target_qty" => "20.00",
                        "unit" => "KG"
                    ]
                ]
            ]
        ];

        $wh = Warehouse::first();
        $prod = CentralProduction::first();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('material kosong');

        $rs = $this->centralService->requestMaterialToWarehouse(
            [], $wh->id, $prod->id
        );

    }

    public function testGenerateCodeCentralShipping()
    {

        $result = $this->centralService->generateCodeProductionShipping('9b0a92ef-0778-43e9-a006-16d2822e8169', '9ad7d971-7a94-4aa5-986c-17bdd46e19f6', 'CENTRALPORIS01');
        print_r($result);
        self::assertIsArray($result);

    }

    public function testGenerateCodeCentralShippingNextMonth()
    {
        Carbon::setTestNow(Carbon::create(2024, 10, 1));
        $result = $this->centralService->generateCodeProductionShipping('9b0a92ef-0778-43e9-a006-16d2822e8169', '9ad7d971-7a94-4aa5-986c-17bdd46e19f6', 'CENTRALPORIS01');
        print_r($result);
        self::assertIsArray($result);
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->centralService = app()->make(CentralProductionServiceImpl::class);
//        DB::table('warehouse_outbounds')->delete();
//        DB::table('central_production_results')->delete();
//        DB::table('central_productions')->delete();
    }

}
