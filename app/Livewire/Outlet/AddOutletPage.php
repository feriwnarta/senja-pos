<?php

namespace App\Livewire\Outlet;

use App\Models\CentralKitchen;
use App\Models\Outlet;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
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
    #[Rule('required|numeric|digits_between:10,15|unique:outlets,phone')]
    public string $phone;
    #[Rule('required|email|unique:outlets,email')]
    public string $email;
    #[Rule('required')]
    public string $selectedCentralKitchen = '';

    public Collection $centralKitchens;


    #[On('save')]
    public function save()
    {

        $this->validate();

        $this->store();
    }

    private function store()
    {
        try {
            DB::beginTransaction();
            $result = Outlet::create([
                'code' => $this->code,
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'central_kitchens_id' => $this->selectedCentralKitchen
            ]);
            DB::commit();


            if ($result) {
                notify()->success('Berhasil menambahkan outlet', 'Sukses');
                $this->reset('code', 'name', 'address', 'phone', 'email');
                return;
            }

            notify()->error('Gagal menambahkan outet', 'Gagal');
            $this->reset('code', 'name', 'address', 'phone', 'email');
            return;


        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Gagal saat pembuatan outlet baru di add outlet');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

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
