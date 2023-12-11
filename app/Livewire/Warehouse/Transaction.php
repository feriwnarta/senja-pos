<?php

namespace App\Livewire\Warehouse;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class Transaction extends Component
{
    public array $outletCentralKitchenDropdown;

    public string $toggle = 'request';

    public string $selected = 'all';
    #[Url(keep: true, as: 'option')]
    public string $urlQuery = 'request';
    public string $id = '';
    private string $type = 'outlet';

    public function render()
    {
        return view('livewire.warehouse.transaction');
    }

    public function mount()
    {
        $this->getOutletCentralKitchen();

        if ($this->urlQuery != 'request' && $this->urlQuery != 'stockIn' && $this->urlQuery != 'stockOut') {
            $this->urlQuery = 'request';
        }
    }

    private function getOutletCentralKitchen()
    {
        try {

            // Ambil semua outlet dengan kolom id dan name
            $outlets = Outlet::all(['id', 'name']);

            // Ambil semua central kitchen dengan kolom id dan name
            $centralKitchens = CentralKitchen::all(['id', 'name']);

            // Gabungkan kedua koleksi menjadi satu collection
            $this->outletCentralKitchenDropdown = $outlets->merge($centralKitchens)->all();


        } catch (Exception $exception) {
            Log::error('gagal mengambil data outlet dan central kitchen di dropdown transaksi');
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
        }
    }

    public function boot()
    {
        $this->selectOutletOrCentral();
    }

    public function selectOutletOrCentral()
    {
        try {
            $outlet = Outlet::find($this->selected);

            if ($outlet != null) {
                $this->id = $outlet->id;
                return;
            }

            $ck = CentralKitchen::find($this->selected);

            if ($ck != null) {
                $this->id = $ck->id;
                $this->type = 'centralKitchen';
                return;
            }

            // TODO: validasi error id tidak ketemu di ck atau outlet
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
        }
    }

    public function create()
    {

        // TODO: jika id kosong maka buat pesan error
        if ($this->id == '') {
            return;
        }


        // jika toggle berupa request
        if ($this->urlQuery == 'request') {
            $this->redirect("/warehouse/transaction/add-transaction?option=request&{$this->type}={$this->id}", true);
        }

    }

    public function toggleChange()
    {
        $this->urlQuery = $this->toggle;
    }
}
