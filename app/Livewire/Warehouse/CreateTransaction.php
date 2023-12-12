<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use DateTime;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateTransaction extends Component
{

    #[Url(as: 'option', keep: true)]
    public string $option = '';
    #[Url(as: 'type', keep: true)]
    public string $type = '';
    #[Url(as: 'id', keep: true)]
    public string $id = '';
    #[Url(as: 'reqId')]
    public string $requestId = '';
    public string $code = '';
    public string $error = '';
    public string $date = '';
    public string $note = '';
    public bool $isCreate = false;
    private bool $isOutlet = false;
    private WarehouseTransactionService $warehouseTransactionService;

    public function render()
    {
        return view('livewire.warehouse.create-transaction');
    }

    public function boot()
    {
        $this->extractUrl();
        $this->date = date('Y-m-d');
        $this->warehouseTransactionService = app()->make(WarehouseTransactionServiceImpl::class);
        $this->findRequestCreated();
    }

    private function extractUrl()
    {
        if ($this->option == null && $this->type == null && $this->id == null) {
            Log::error('gagal buat permintaan stock');
            $this->error = 'Ada yang salah dengan format url, kembali dan masuk lagi';
            return;
        }

        if ($this->type != 'outlet' && $this->type != 'centralKitchen') {
            Log::error('gagal buat permintaan stock');
            $this->error = 'Pastikan tipe adalah outlet atau central kitchen';
            return;
        }

        if ($this->id == '') {
            $this->error = 'Pastikan anda sudah menentukan gudang pembuat permintaan';
            Log::error('gagal buat permintaan stock');
            return;
        }

        if ($this->type == 'outlet') {
            $this->isOutlet = true;
        }
    }

    private function findRequestCreated()
    {
        if ($this->requestId != '') {
            $result = RequestStock::find($this->requestId);

            if ($result != null) {
                $this->isCreate = true;
                $this->setFieldNextReq($result);
            }
        }
    }

    /**
     * @param RequestStock $result
     * @return void
     * @throws Exception
     */
    private function setFieldNextReq(RequestStock $result): void
    {
        $this->code = $result->code;
        $date = $result->created_at;
        $dateTime = new DateTime($date);
        $dateTime = $dateTime->format('d F Y');


        $this->date = $dateTime;
        $this->note = ($result->note == null) ? 'Tanpa catatan' : $result->note;
    }

    /**
     * proses pembuatan permintaan baru
     * generate code permintaan
     * @return void
     */
    public function create()
    {
        try {
            $result = $this->warehouseTransactionService->createRequest($this->isOutlet, $this->id, ($this->note == '') ? null : $this->note);
            $this->isCreate = true;
            $this->requestId = $result->id;
            $this->setFieldNextReq($result);
            notify()->success('Permintaan berhasil dibuat', 'Sukses');
            return;
        } catch (Exception $exception) {
            Log::debug('gagal membuat request');
            Log::error($exception->getMessage());
        } catch (LockTimeoutException $exception) {
            Log::debug('gagal membuat request karena sedang ada lock');
            Log::error($exception->getMessage());
            notify()->warning('Sedang ada proses permintaan yang sedang terjadi juga, silahkan tunggu sebentar lalu coba lagi', 'Gagal');
            return;
        }


    }


}
