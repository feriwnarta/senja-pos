<?php

namespace App\Livewire\Warehouse;

use App\Models\WarehouseOutbound;
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
}
