<?php

namespace App\Livewire\Warehouse;

use App\Models\WarehouseOutbound;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * TODO: Kelas ini seharusnya sebagai kelas Transaction detail out
 */
class TransactionDetail extends Component
{
    #[Url(as: 'wouId', keep: true)]
    public string $outboundId = '';

    public string $error = '';

    public string $mode = '';

    public WarehouseOutbound $warehouseOutbound;
    public array $outboundItems = [];
    private CentralProductionService $productionService;

    public function render()
    {
        return view('livewire.warehouse.transaction-detail');
    }

    public function boot()
    {
        $this->initMode();
        Log::debug($this->error);
        return;

        $this->searchOutboundHistory();
    }

    private function initMode()

    {
        $result = $this->findOutboundById($this->outboundId);


        if ($result == null) {

            $this->error = 'Detail stok keluar tidak valid, harap kembali dan muat ulang';
            return;
        }

        $this->warehouseOutbound = $result;
    }


    private function findOutboundById(string $id)
    {
        try {

            return WarehouseOutbound::findOrFail($id);

        } catch (Exception $exception) {
            Log::error('outbound id tidak ditemukan saat melihat detail transaksi');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return null;
        }
    }

    private function searchOutboundHistory()
    {
        try {
            $latestHistory = $this->warehouseOutbound->history()->latest()->firstOrFail();
            $status = $latestHistory->status;

            Log::debug($status);

            if ($status == 'Bahan dikirim') {
                $this->mode = 'view';
            }

        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
        }

    }

    /**
     * terima dan lanjutkan proses keluar barang dari gudang
     * @return void
     */
    public function acceptAndNext()
    {

        // validasi item harus ada
        if ($this->warehouseOutbound->outboundItem->isEmpty()) {
            notify()->error('Item gagal didapatkan', 'Error');
            return;
        }

        // generate code produksi
        $this->saveCodeItemOut();

    }

    private function saveCodeItemOut()
    {

        try {
            $this->productionService = app()->make(CentralProductionServiceImpl::class);

            // validasi outbound id dan warehouse id
            if ($this->outboundId == '') {
                $this->error = 'Detail stok keluar tidak valid, harap kembali dan muat ulang';
                return;
            }

            if ($this->warehouseOutbound == null) {
                $this->error = 'Detail stok keluar tidak valid, harap kembali dan muat ulang';
                return;
            }


            // simpan kode generasi baru
            $result = $this->productionService->saveCodeItemOut($this->outboundId, $this->warehouseOutbound->warehouses_id);

            if ($result) {
                notify()->success('Berhasil menerima permintaan', 'Sukses');
                $this->initMode();
                return;
            }
            notify()->error('Gagal menerima permintaan', 'Gagal');
            return;

        } catch (Exception $exception) {
            Log::error('gagal menyimpan kode item keluar');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            notify()->error('Gagal menerima permintaan', 'Gagal');

        }
    }

    /**
     * kirim item dari gudang ke central produksi / outlet
     * validasi bahwa stoknya sesuai dan stok aktual tidak lebih besar dari nilai dikirim
     * hitung nilai cogsnya
     * update status menjadi dikirim
     * @return void
     */
    public function sendItem()
    {

        // validasi item
        $this->validate([
            'outboundItems.*.qty_send' => [
                'required',
                'numeric',
                'min:0.01',

                // validasi jumlah permintaan item tidak boleh lebih dari qty on hand
                function ($attribute, $value, $fail) {
                    // Ambil index dari item yang sedang divalidasi

                    // Ekstrak nilai numerik dari string menggunakan ekspresi reguler
                    preg_match('/\d+/', $attribute, $matches);

                    // Ambil nilai pertama dari hasil pencocokan
                    $index = $matches[0] ?? null;

                    // Ambil nilai qty_on_hand untuk item tersebut dari array outboundItems
                    $qtyOnHand = $this->outboundItems[$index]['qty_on_hand'] ?? 0;


                    // Validasi bahwa qty_send tidak melebihi qty_on_hand
                    if ($value > $qtyOnHand) {
                        $fail("The $attribute may not be greater than qty_on_hand.");
                    }
                },
            ],
        ]);

        // lakukan proses pengurangan stock inventory valuation
        $this->reduceInventory($this->outboundItems, $this->outboundId);

    }

    private function reduceInventory(array $items, string $outboundId)
    {
        Log::info('proses pengurangan stock inventory valuation');


        try {
            DB::beginTransaction();
            // Lakukan update history request stock
            $resultUpdate = $this->updateHistoryRequestStock();

            if ($resultUpdate) {
                // Lakukan pengurangan stok item untuk pengiriman
                $warehouseService = app(WarehouseTransactionServiceImpl::class);
                $result = $warehouseService->reduceStockItemShipping($items, $outboundId);

                if ($result) {
                    DB::commit();
                    notify()->success('Berhasil kirim barang', 'Sukses');
                    $this->mode = 'view';
                    // Pemberitahuan sukses

                    return;
                }
            }

            DB::rollBack();
            // Pemberitahuan kesalahan saat proses pengiriman
            notify()->error('Gagal melakukan proses pengiriman', 'Error');

        } catch (Exception $exception) {
            DB::rollBack();
            // Log dan pemberitahuan kesalahan
            Log::error('Gagal mengurangi stok barang saat proses keluar dari gudang');
            Log::error($exception->getMessage());
            notify()->error('Gagal melakukan proses pengiriman', 'Error');
        }

    }

    private function updateHistoryRequestStock()
    {

        return $this->warehouseOutbound->production->requestStock->requestStockHistory()->create([
            'request_stocks_id' => $this->warehouseOutbound->production->requestStock->id,
            'desc' => 'Bahan dalam proses pengiriman',
            'status' => 'Bahan dikirim',
        ]);


    }


}
