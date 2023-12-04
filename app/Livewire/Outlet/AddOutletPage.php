<?php

namespace App\Livewire\Outlet;

use App\Models\CentralKitchen;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddOutletPage extends Component
{

    #[Rule('required|min:5|unique:central_kitchens,code')]
    public string $code;
    #[Rule('required|min:5')]
    public string $name;
    #[Rule('required|min:5')]
    public string $address;
    public string $phone;
    public string $email;
    public string $selectedCentralKitchen;

    public Collection $centralKitchens;


    public function placeholder()
    {
        return <<<'HTML'
        <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        HTML;

    }

    public function boot()
    {
        $this->getCentralKitchen();
    }

    private function getCentralKitchen()
    {
        try {

            $this->centralKitchens = CentralKitchen::all(['id', 'name']);

        } catch (Exception $exception) {
            Log::error('Gagal mendapatkan data central kitchen saat pembuatan outlet baru');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    public function render()
    {

        return view('livewire.outlet.add-outlet-page');
    }


}
