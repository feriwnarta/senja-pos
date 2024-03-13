<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralProduction;
use App\Models\CentralProductionRemaining;
use App\Models\RequestStock;
use App\Models\RequestStockHistory;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductionDetail extends Component
{
    #[Url(as: 'reqId')]
    public string $requestId;

    public string $error = '';

    public RequestStock $requestStock;

    public string $status = 'Baru';
    public ?CentralProduction $production;
    public array $components;
    public array $productionComponentSave;
    public string $productionId;
    public string $note = '';
    public array $itemRemaining = [];
    public bool $isSaveOnCentral = true;
    private CentralProductionService $productionService;

    /**
     * lakukan proses validasi item yang diterima oleh central kitchen dari gudang
     * @return void
     */
    public function validateAndAccept()
    {

        // validasi item yang diterima
        $this->validate([
            'components.*.qty_accept' => 'required|numeric|min:0',
        ]);

        // proses menerima item yang dikirim
        Log::debug($this->components);


        if (!isset($this->production) && $this->production == null) {
            $this->production = $this->findProductionById($this->requestId);
        }

        $outboundId = $this->production->outbound()->latest()->first()->id;

        $this->storeItemReceipt($this->components, $outboundId);

    }

    /**
     * cari produksi berdasarkan id
     * @param $id
     * @return null
     */
    private function findProductionById($id)
    {
        try {
            return CentralProduction::where('request_stocks_id', $id)->firstOrFail();

        } catch (Exception $exception) {
            notify()->error('Ada sesuatu yang salah', 'Error');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return null;
        }

    }

    /**
     * validasi item receipt
     * @return void
     */
    private function storeItemReceipt(array $items, string $outboundId)
    {
        try {

            $this->productionService = app()->make(CentralProductionServiceImpl::class);
            $result = $this->productionService->processItemReceiptProduction($items, $outboundId);

            if ($result) {
                notify()->success('Berhasil validasi dan terima bahan', 'Sukses');
                $this->status = $this->findRequestStatus() == null ? 'Baru' : $this->findRequestStatus();
                $this->delegateProcess($this->status);
                return;
            }

            notify()->error('Gagal validasi dan terima bahan', 'Gagal');

        } catch (Exception $exception) {
            notify()->error('Gagal menerima bahan', 'Error');
        }
    }

    /**
     * lakukan pengecekan apakah produksi sudah diterima
     * jika sudah diterima lakukan pengecekan untuk mendapatkan status nya
     * jika hanya sampa diterima maka lakukan proses permintaan bahan terlebih dahulu
     * @return void
     */
    private function findRequestStatus()
    {
        if ($this->requestId != '') {

            // cek history
            try {
                return RequestStockHistory::where('request_stocks_id', $this->requestId)->latest()->firstOrFail()->status;

            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                Log::error($exception->getTraceAsString());
                return null;
            }
        }
    }

    /**
     * tentukan flow dari produksi yang diklik, apakah sedang dalam
     * permintaan bahan, sedang dalam proses produksi, ataupun penyelesaian produksi
     * @param $status
     * @return void
     */
    private function delegateProcess($status)
    {
        $this->status = $status;
        switch ($status) {
            case 'Produksi diterima' :
                $this->setProduction();
                $this->createRequestMaterial($this->requestId);
                break;

            case 'Komponen produksi disimpan' :
                $this->setProduction();
                $this->detailComponentSaved($this->production);
                break;

            case "Bahan dikirim":
            case 'Membuat permintaan bahan' :
                $this->setProduction();
                $this->allItemRequest();
                break;

            case "Bahan diterima" :
                $this->setProduction();
                $this->resultProduction();
                break;

            case "Produksi selesai" :
                $this->setProduction();
                $this->resultProduction();
                $this->getItemWithRemaining();
                break;

            case "Menunggu pengiriman" :
            case "Selesai produksi":
                $this->setProduction();
                break;

        }

    }

    /**
     *  inisialisasi model production melalui fungsi ini
     * @return void
     */
    private function setProduction()
    {
        // handling error request id kosong
        if ($this->requestId == '') {

            return;
        }

        // cari produksi dari request id
        $this->production = $this->findProductionById($this->requestId);

        Log::info('set production' . $this->production);

        if ($this->production == null) {
            notify()->warning('Tidak bisa mendapatkan data produksi, harap ulangi permintaan', 'Peringatan');
            return;
        }

    }

    /**
     * buat request material ke gudang jika statusnya adalah permintaan diterima
     * @return void
     */
    private function createRequestMaterial($requestId)
    {
        if ($requestId != '') {

            try {

                // jika request stock kosong maka isi request stock
                if (!isset($this->requestStock)) {
                    $this->requestStock = RequestStock::findOrFail($requestId);
                }

                if (isset($this->requestStock) && $this->requestStock != null && $this->requestStock->requestStockDetail->isNotEmpty()) {

                    // mapping isi request stock detail
                    $components = $this->requestStock->requestStockDetail
                        ->lazy(10)
                        ->map(function ($detail) {
                            $detail->load([
                                'item.recipe.recipeDetail', // Load all recipe details
                            ]);

                            $recipes = [];


                            foreach ($detail->item->recipe as $recipe) {

                                foreach ($recipe->recipeDetail as $recipeDetail) {

                                    $recipes[] = [
                                        'isChecked' => false,
                                        'id' => $recipeDetail->id,
                                        'item_component_id' => $recipeDetail->item->id,
                                        'item_component_name' => $recipeDetail->item->name,
                                        'item_component_unit' => $recipeDetail->item->unit->name,
                                        'item_component_usage' => number_format($detail->qty * $recipeDetail->usage, 0, '.', '.'),
                                        'qty_request' => 0,
                                    ];
                                }
                            }

                            return [
                                'item' => [
                                    'id' => $detail->item->id,
                                    'name' => $detail->item->name,
                                ],
                                'recipe' => $recipes,
                            ];
                        })
                        ->toArray(); // Convert to array if needed

                    if (!empty($components)) {
                        $this->components = $components;
                        Log::debug($components);
                        return;
                    }

                    notify()->error('Gagal mendapatkan resep', 'error');
                }


            } catch (Exception $exception) {
                Log::error('gagal dapatkan item yang dibutuhkan untuk produksi dari request material central production');
                $exception->getMessage();
                $exception->getTraceAsString();
            }

        }

    }

    /**
     * tampilkan detail komponen yang sudah disimpan saat proses produksi
     * @return void
     */
    private function detailComponentSaved($production)
    {
        try {
            $productionComponentSave = $production->result->load('targetItem', 'component')->groupBy('targetItem.id')->map(function ($groupedItems) {
                $targetItem = $groupedItems->first()->targetItem;

                $components = $groupedItems->map(function ($resultProduction) {

                    return [
                        'id' => $resultProduction->component->id,
                        'name' => $resultProduction->component->name,
                        'target_qty' => $resultProduction->qty_target,
                        'unit' => $resultProduction->component->unit->name,
                    ];
                });

                return [
                    'targetItem' => [
                        'id' => $targetItem->id,
                        'name' => $targetItem->name,
                    ],
                    'components' => $components->all(),
                ];
            })->toArray();

            if (isset($productionComponentSave) && !empty($productionComponentSave)) {
                $this->productionComponentSave = $productionComponentSave;
            }


        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

        }
    }

    /**
     * dapatkan data item yang diminta dari gudang
     * @return void
     */
    private function allItemRequest()
    {
        try {
            if (isset($this->production) && $this->production != null) {

                Log::debug($this->production->outbound);

                $outboundItem = $this->production->outbound()->first()->outboundItem;

                if ($outboundItem != null) {

                    $result = $outboundItem->map(function ($item) {

                        Log::debug($item);
                        return [
                            'item_id' => $item->item->id,
                            'name' => $item->item->name,
                            'request_qty' => $item->qty,
                            'send_qty' => $item->qty_send,
                            'qty_accept' => number_format($item->qty_send, 0, '.', '.'),
                            'unit' => $item->item->unit->name,
                        ];
                    });

                    $this->components = $result->toArray();
                }

            }

        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
        }

    }

    /**
     * dapatkan hasil produksi
     * @return void
     */
    private function resultProduction()
    {

        if (!isset($this->production) || $this->production == null) {
            $this->production = $this->findProductionById($this->requestId);
        }

        try {
            $productionComponentSave = $this->production->result->load('targetItem', 'component')->groupBy('targetItem.id')->map(function ($groupedItems) {
                $targetItem = $groupedItems->first()->targetItem;
                $targetQty = $groupedItems->first()->qty_target;


                return [
                    'result_id' => $groupedItems->first()->id,
                    'id' => $targetItem->id,
                    'name' => $targetItem->name,
                    'target_qty' => $targetQty,
                    'unit' => $targetItem->unit->name,
                    'result_qty' => number_format($groupedItems->first()->qty_result, 0, '.', '.'),
                ];
            })->toArray();

            $this->components = $productionComponentSave;


        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    /**
     * dapatkan array item dengan input sisa
     * @return void
     */
    private function getItemWithRemaining()
    {

        try {

            if (!isset($this->production) || $this->production == null) {
                $this->production = $this->findProductionById($this->requestId);
            }


            $data = $this->production->outbound->flatMap(function ($outbound) {
                $outbound->load('receipt.detail.item.unit');

                return $outbound->receipt->map(function ($receipt) {
                    $receiptDetail = $receipt->detail->first();
                    $item = $receiptDetail ? $receiptDetail->item : null;

                    return [
                        'item_id' => optional($item)->id,
                        'item_name' => optional($item)->name,
                        'qty_accept' => optional($receiptDetail)->qty_accept,
                        'qty_use' => optional($receiptDetail)->qty_accept,
                        'unit' => optional($item->unit)->name,
                        'isChecked' => '',
                    ];
                });
            })->toArray();


            $this->itemRemaining = $data;

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan item untuk ditampilkan di item sisa produksi');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    /***
     * proses menyimpan permintaan bahan yang dibutuhkan oleh central kitchen ke gudang
     * @return void
     */
    public function saveRequest()
    {

        // validasi item yang dipilih
        $this->validate([
            'components.*.recipe' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Periksa apakah ada setidaknya satu isChecked dalam semua recipe yang bernilai true
                    if (!collect($value)->pluck('isChecked')->contains(true)) {
                        $fail('Harap pilih bahan yang ingin diminta');
                    }
                },
            ],
        ]);


        // proses simpan permintaan dari production service
        $componentChecked = $this->filterCheckedItems($this->components);


        // next step
        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);
            // lakukan pencarian central kitchen id

            if (isset($this->production) && $this->production != null) {
                $result = $this->productionService->saveComponent($this->production->id, $componentChecked);

                if ($result) {
                    notify()->success('Berhasil simpan komponen resep', 'Sukses');

                    // proses detail komponen setelah disimpan
                    $this->status = $this->findRequestStatus() == null ? 'Baru' : $this->findRequestStatus();
                    $this->delegateProcess($this->status);
                }
            }


        } catch (Exception $exception) {
            notify()->error('Ada sesuatu yang salah', 'Error');
            return;
        }


    }

    private function filterCheckedItems($data)
    {
        $result = [];

        foreach ($data as $category) {
            $filteredRecipe = array_filter($category['recipe'], function ($recipe) {
                return $recipe['isChecked'];
            });

            if (!empty($filteredRecipe)) {
                $result[] = [
                    'item' => $category['item'],
                    'recipe' => array_values($filteredRecipe) // Mengatur ulang indeks array
                ];
            }
        }

        return $result;
    }

    public function render()
    {
        return view('livewire.central-kitchen.production-detail');
    }

    public function mount()
    {
        $this->checkProductionId(id: $this->requestId);
        // dapatkan status
        $this->status = $this->findRequestStatus() == null ? 'Baru' : $this->findRequestStatus();

        $this->delegateProcess($this->status);
    }

    /**
     * funsi ini digunakan untuk mencari request stock berdasarkan request stock id url parameter
     * @param string $id
     * @return void
     */
    private function checkProductionId(string $id)
    {

        if ($id == '') {
            $this->redirect('/central-kitchen/production', true);
            return;
        }

        try {

            $this->requestStock = RequestStock::findOrFail($id);

        } catch (Exception $exception) {
            Log::error('gagal dapatkan model request stock di detail produksi');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }


    }

    /**
     * terima produksi dan lanjutkan, generate nomor produksi
     * @return void
     */
    public function acceptAndNext()
    {

        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);
            // lakukan pencarian central kitchen id

            $centralId = RequestStock::findOrFail($this->requestId)->warehouse->centralKitchen->first()->id;

            // buat produksi
            $result = $this->productionService->createProduction($this->requestId, $centralId);

            if ($result == 'failed') {
                Log::error('Error saat membuat produksi:');
                notify()->error('Gagal membuat produksi', 'error');
                return;
            }


            notify()->success('Berhasil membuat produksi', 'sukses');
            $this->productionId = $result->id;

            // dapatkan status
            $status = $this->findRequestStatus();
            $this->status = $status;

            // proses pesan error bahwa status tidak bisa didapatkan
            if (!isset($status) && $status == null) {
                return;
            }

            // panggil delegate process, untuk menentukan flow dari produksi saat ini
            $this->delegateProcess($status);


        } catch (Exception $exception) {
            Log::error('Error saat membuat produksi:', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            notify()->error('Gagal membuat produksi, ', 'Error'); // Gunakan pesan yang sama untuk konsistensi
        }

    }

    /**
     * lakukan permintaan ke gudang untuk bahan yang sudah ditentukan oleh central kitchen
     * @return void
     */
    public function requestMaterialToWarehouse()
    {

        // tampikan pesan error produski save kosong
        if (!isset($this->productionComponentSave) && empty($this->productionComponentSave)) {
            notify()->error('Gagal mendapatkan komponen', 'Error');
            Log::error('gagal mendapatkan data komponen yang disimpan di produksi modul');
            return;
        }

        if (!isset($this->requestStock) && $this->requestStock == null) {
            $this->requestStock = $this->checkProductionId($this->production->id);
        }

        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);
            $result = $this->productionService->requestMaterialToWarehouse(materials: $this->productionComponentSave, warehouseId: $this->requestStock->warehouses_id, productionId: $this->production->id, requestId: $this->requestId);

            if ($result) {
                notify()->success('Berhasil membuat permintaan bahan', 'Sukses');
                $this->status = $this->findRequestStatus() == null ? 'Baru' : $this->findRequestStatus();
                $this->delegateProcess($this->status);
                return;
            }

            notify()->error('Gagal melakukan permintaan bahan', 'Error');
        } catch (Exception $exception) {
            notify()->error('Gagal melakukan permintaan bahan', 'Error');
            Log::error('gagal meminta bahan');
            return;
        }

    }

    public function validateItemRemaining()
    {
        $this->validate([
            'itemRemaining' => 'required']);

        Log::info('proses validasi pengiriman dan penyimpanan bahan sisa');


        $this->storeItemProductionRemaining($this->itemRemaining, $this->isSaveOnCentral, $this->production->id);


    }

    private function storeItemProductionRemaining(array $items, bool $isSaveOnCentral, string $productionId)
    {

        try {


            DB::beginTransaction();


            $remaining = CentralProductionRemaining::create([
                'central_productions_id' => $productionId,
                'status' => ($this->isSaveOnCentral) ? 'CENTRAL' : 'WAREHOUSE'
            ]);

            $remaining->production->requestStock->requestStockHistory()->create([
                'desc' => 'Menyimpan bahan sisa produksi',
                'status' => 'Menunggu pengiriman',
            ]);


            $itemRemaining = [];

            foreach ($items as $item) {

                $total = $item['qty_accept'] - $item['qty_use'];

                $itemRemaining[] = [
                    'central_productions_remaining_id' => $remaining->id,
                    'items_id' => $item['item_id'],
                    'qty_remaining' => $total,
                ];

            }

            $result = $remaining->detail()->createMany($itemRemaining);

            DB::commit();

            if ($result) {
                notify()->success('Berhasil validasi dan simpan sisa produksi', 'Sukses');
                // dapatkan status
                $status = $this->findRequestStatus();
                $this->delegateProcess($status);
                return;
            }

            notify()->error('Gagal validasi dan simpan sisa produksi', 'Gagal');


        } catch (Exception $exception) {
            DB::rollBack();
            notify()->error('Gagal validasi dan simpan sisa produksi', 'Gagal');
            Log::error('gagal menyimpan sisa bahan');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    public function sendItem()
    {

        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);

            DB::beginTransaction();

            if (!isset($this->requestStock) && $this->requestStock == null) {
                $this->requestStock = $this->checkProductionId($this->production->id);
            }

            $result = $this->requestStock->requestStockHistory()->create([
                'desc' => 'Selesai proses produksi, hasil produksi dikirim',
                'status' => 'Selesai produksi',
            ]);

            if (empty($this->components)) {
                $this->resultProduction();
            }


            // buat pengiriman
            $this->productionService->createProductionShipping($this->production->id, $this->production->centralKitchen->id, $this->production->centralKitchen->code);

            // buat draft item receipt
            $this->productionService->createItemReceipt($this->production->outbound->last()->warehouse->id, $this->components, $this->production);

            DB::commit();

            if ($result) {
                notify()->success('Berhasil menyelesaikan produksi dan mengirim bahan', 'Sukses');
                // dapatkan status
                $status = $this->findRequestStatus();
                $this->delegateProcess($status);
                return;
            }

            notify()->error('Berhasil menyelesaikan produksi dan mengirim bahan', 'Gagal');


        } catch (Exception $exception) {
            DB::rollBack();
            notify()->error('Berhasil menyelesaikan produksi dan mengirim bahan', 'Gagal');
            Log::error('gagal menyelesaikan proses pengiriman hasil produksi');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    /**
     * simpan hasil produksi
     * @return void
     */
    private function storeResultProduction(array $items, string $productionId, string $note)
    {
        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);
            $result = $this->productionService->finishProduction($items, $productionId, $note);

            if ($result) {
                notify()->success('Berhasil menyelesaikan produksi', 'Sukses');
                $status = $this->findRequestStatus();
                $this->delegateProcess($status);
                return;
            }

            notify()->error('Gagal menyelesaikan produksi', 'Error');

        } catch (Exception $exception) {
            Log::error('gagal menyimpan hasil produksi dari production detail');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            notify()->error('Gagal menyelesaikan produksi', 'Error');
        }

    }

    /**
     * menyelesaikan proses produski,
     * menyimpan result produksi dan catatan ke central
     * production result
     * @return void
     */
    public function finishProduction()
    {

        // validasi result qty
        $this->validate([
            'components.*.result_qty' => 'required|numeric|min:1'], [
            'components.*.result_qty' => 'The yield quantity must be greater than 0'
        ]);
        Log:
        info('Proses selesai produksi');
        

        if (!isset($this->production) && $this->production == null) {
            $this->production = $this->findProductionById($this->requestId);
        }

        $productionId = $this->production->id;


        $this->storeResultProduction($this->components, $productionId, $this->note);
    }
}
