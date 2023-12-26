<?php

namespace App\Livewire\Warehouse;

use App\Models\WarehouseOutbound;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class TransactionDetail extends Component
{

    #[Url(as: 'option', keep: true)]
    public string $option = '';

    #[Url(as: 'wouId', keep: true)]
    public string $outboundId = '';

    public string $error = '';

    public WarehouseOutbound $warehouseOutbound;
    public array $outboundItems;
    private CentralProductionService $productionService;

    public function render()
    {
        return view('livewire.warehouse.transaction-detail');
    }

    public function boot()
    {
        $this->extractUrl();
        $result = $this->findOutboundById($this->outboundId);

        if ($result == null) {
            $this->error = 'Detail stok keluar tidak valid, harap kembali dan muat ulang';
            return;
        }

        $this->warehouseOutbound = $result;
    }

    /**
     * lakukan proses extract url untuk memvalidasi option dan outbounid
     * @return void
     */

    private function extractUrl()
    {
        $validOption = ['request', 'stockIn', 'stockOut'];
        if (!in_array($this->option, $validOption)) {
            $this->error = 'Option tidak sesuai, harap kembali dan muat ulang';
            return;
        }

        if ($this->outboundId == '') {
            $this->error = 'Detail stok keluar tidak valid, harap kembali dan muat ulang';
            return;
        }
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
}
