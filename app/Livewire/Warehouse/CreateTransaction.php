<?php

namespace App\Livewire\Warehouse;

use App\Models\RequestStock;
use App\Models\Warehouse;
use App\Service\Impl\WarehouseTransactionServiceImpl;
use App\Service\WarehouseTransactionService;
use DateTime;
use Exception;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
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
    public Collection $items;
    public array $selected;
    public Warehouse $warehouse;
    public ?string $nextCursor = null;
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
                $this->getAllItemByWarehouseId();
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
     * setelah berhasil membuat permintaan pengisian ulang stock
     * maka ambil data item berdasarkan id warehouse yang sudah dipilih
     * @return void
     */
    private function getAllItemByWarehouseId()
    {


        try {

            // proses permintaan dari gudang pusat
            if ($this->type == 'centralKitchen') {


                if ($this->nextCursor == null && empty($this->warehouse)) {


                    // cari warehouse berdasarkan id
                    $this->warehouse = Warehouse::findOrFail($this->id);

                    if ($this->warehouse != null && $this->warehouse->centralKitchen->isNotEmpty()) {
                        $result = $this->warehouse->centralKitchen->each(function ($central) {
                            $items = $central->item()->cursorPaginate(10);

                            $allItems = collect();
                            $items->each(function ($item) use ($allItems) {
                                $allItems->push($item);
                            });

                            $this->items = $allItems;

                            $this->nextCursor = $items->toArray()['next_cursor'];

                        });


                    }
                }


            }

            // TODO: Proses permintaan dari outlet


        } catch (ModelNotFoundException $modelNotFoundException) {
            Log::error('warehouse tidak ketemu');
            Log::error($modelNotFoundException->getMessage());
            Log::error($modelNotFoundException->getTraceAsString());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    /**
     * load more item saat membuat permintaan produksi
     * @return void
     */
    public function loadMoreItem()
    {

        Log::debug($this->nextCursor);

        if ($this->nextCursor != null) {
            if ($this->warehouse->centralKitchen->isNotEmpty()) {
                $result = $this->warehouse->centralKitchen->each(function ($central) {
                    $items = $central->item()->cursorPaginate(10, ['*'], 'cursor', $this->nextCursor);
                    $allItems = collect();
                    $items->each(function ($item) use ($allItems) {
                        $allItems->push($item);
                    });

                    // Menggabungkan koleksi existing dengan koleksi baru
                    $this->items = $this->items->merge($allItems);

                    $this->nextCursor = $items->nextCursor();
                });
            }
        }
    }

    /**
     * tambahkan ke array selected item mana saja yang dipilih
     * @param string $id
     * @return void
     */
    public function selectItem(string $id)
    {
        // Pastikan $id adalah nilai yang valid
        if (!empty($id)) {
            // Cari indeks elemen dengan ID yang ditentukan
            $index = array_search($id, array_column($this->selected, 'id'));

            // Jika $id sudah ada, hapus elemen dari array
            if ($index !== false) {
                unset($this->selected[$index]);
            } else {
                // Jika $id belum ada, tambahkan ke array
                $this->selected[] = [
                    'id' => $id,
                    'itemReq' => ''
                ];
            }
        } else {
            Log::error('Id tidak ditemukan saat memilih item permintaan stock');
            notify()->error('ada sesuatu yang salah silahkan muat ulang', 'Gagal');
            return;
        }

    }

    /**
     * proses pembuatan permintaan baru
     * generate code permintaan
     * @return void
     */
    public function create()
    {

        // jika is create false maka jalankan proses pembuatan stok pertama kali
        if ($this->isCreate == false) {
            $this->storeRequest();
            $this->findRequestCreated();
            return;
        }

        $this->finishCreateRequest();
    }

    private function storeRequest()
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

    /**
     * selesaikan pembuatan permintaan stok dari warehouse pusat ke central kitchen
     * validasi item yang dipilih
     * @return void
     */
    private function finishCreateRequest()
    {
        // jika item belum dipilih
        if (empty($this->selected)) {
            notify()->warning('Harap pilih item sebelum melanjutkan', 'Peringatan');
            return;
        }

        // proses simpan detail permintaan
        try {
            $result = $this->warehouseTransactionService->finishRequest($this->requestId, $this->selected);

            if ($result == 'success') {
                notify()->success('Berhasil menyelesaikan permintaan stock');
                $this->reset('requestId', 'code', 'error', 'note', 'isCreate', 'items', 'selected', 'nextCursor');
                $this->date = date('Y-m-d');
                return;
            }
        } catch (Exception $exception) {
            notify()->error('Gagal menyelesaikan permintaan stok', 'Gagal');
            return;
        }
    }


}
