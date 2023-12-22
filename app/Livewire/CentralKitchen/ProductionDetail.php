<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralProduction;
use App\Models\RequestStock;
use App\Models\RequestStockHistory;
use App\Service\CentralProductionService;
use App\Service\Impl\CentralProductionServiceImpl;
use Exception;
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
    private CentralProductionService $productionService;

    public function boot()
    {
        // dapatkan status
        $this->status = $this->findRequestStatus() == null ? 'Baru' : $this->findRequestStatus();

        $this->delegateProcess($this->status);
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


        switch ($status) {
            case 'Produksi diterima' :

                $this->setProduction();
                $this->createRequestMaterial($this->requestId);
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

        if ($this->production == null) {
            notify()->warning('Tidak bisa mendapatkan data produksi, harap ulangi permintaan', 'Peringatan');
            return;
        }

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
                                        'item_component_usage' => number_format($detail->qty * $recipeDetail->usage, 2, '.', '.'),
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

    public function test()
    {
        Log::info($this->components);
    }

    public function render()
    {
        return view('livewire.central-kitchen.production-detail');
    }

    public function mount()
    {
        $this->checkProductionId(id: $this->requestId);
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

    /***
     * proses menyimpan permintaan bahan yang dibutuhkan oleh central kitchen ke gudang
     * @return void
     */
    public function saveRequest()
    {

        // validasi item yang dipilih
        $this->validate([
            'components.*.recipe.*.isChecked' => function ($attribute, $value, $fail) {
                // Periksa apakah ada salah satu isChecked dalam array yang bernilai true
                if (!collect($this->components)->pluck('recipe')->flatten(1)->pluck('isChecked')->contains(true)) {
                    $fail('Minimal salah satu component harus dicentang.');

                }
            },
        ]);


        // proses simpan permintaan dari production service

        // next step
    }
}
