<?php

namespace App\Livewire\CentralKitchen;

use App\Models\CentralKitchen;
use App\Models\RequestStock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Production extends Component
{
    use WithPagination;

    public Collection $centralKitchens;

    public function boot()
    {
        $this->loadCentralKitchen();
    }


    private function loadCentralKitchen()
    {

        try {

            $this->centralKitchens = CentralKitchen::all();


        } catch (Exception $exception) {
            Log::error($exception);

        }
    }

    public function detailProduction($id)
    {
        $this->processDetail($id);
    }

    private function processDetail(string $id)
    {

        if ($id == '') {
            return;
        }

        try {
            Log::info("akses detail production $id");
            $request = RequestStock::findOrFail($id);
            $this->redirect("/central-kitchen/production/detail-production?productionId=$id", true);
        } catch (Exception $exception) {

            notify()->error('Ada kesalahan saat mendapatkan detail production', 'Error');
            Log::error("gagal mendapatkan detail production $id");
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

    public function render()
    {
        // paginasi request stock yang hanya memiliki item produksi didalamnya
        return view('livewire.central-kitchen.production', ['requestStock' => RequestStock::has('requestStockDetail', '>=', 1)
            ->whereHas('requestStockDetail', function ($query) {
                $query->where('type', 'PRODUCE');
            })
            ->with(['requestStockDetail' => function ($query) {
                $query->where('type', 'PRODUCE');
            }])
            ->paginate(10)]);
    }
}
