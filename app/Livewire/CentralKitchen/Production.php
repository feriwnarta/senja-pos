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

    public string $selected = 'all';


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
        Log::debug($this->selected);
        // paginasi request stock yang hanya memiliki item produksi didalamnya
        return
            view('livewire.central-kitchen.production', ['requestStock' => RequestStock::whereHas('warehouse', function ($query) {
                $query->whereHas('centralKitchen', function ($query) {
                    $query->where('central_kitchens.id', $this->selected);
                });
            })
                ->with(['requestStockDetail' => function ($query) {
                    $query->where('type', 'PRODUCE');
                }])->orderBy('id', 'DESC')
                ->paginate(10)]);

    }

    /**
     * tampilkan data berdasarkan central kitchen
     * @return void
     */
    public function centralKitchenChange()
    {
        // jika status dropdown saat ini bukanlah all
        if ($this->selected != 'all') {

            // cari central kitchen
            try {

                $centralKitchen = CentralKitchen::findOrFail($this->selected);

            } catch (Exception $exception) {
                Log::error('gagal dapatkan data central kitchen dari dropdown produksi');
                Log::error($exception->getMessage());
                Log::error($exception->getTraceAsString());
            }

        }
    }
}
