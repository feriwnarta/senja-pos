<?php

namespace App\Livewire\CentralKitchen;

use App\Models\RequestStock;
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

    #[Url(as: 'prodId')]
    public string $productionId;

    public string $error = '';

    public RequestStock $requestStock;

    private CentralProductionService $productionService;

    public function render()
    {
        return view('livewire.central-kitchen.production-detail');
    }

    public function mount()
    {
        $this->checkProductionId(id: $this->requestId);
    }

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
            $this->productionId = $result;

        } catch (Exception $exception) {
            Log::error('Error saat membuat produksi:', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            notify()->error('Gagal membuat produksi, ', 'Error'); // Gunakan pesan yang sama untuk konsistensi
        }

    }


}
