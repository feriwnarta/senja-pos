<?php

namespace App\Livewire\Warehouse;

use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Models\CategoryItem;
use App\Models\CentralKitchen;
use App\Models\Outlet;
use App\Repository\Warehuouse\WarehouseRepository;
use App\Service\Warehouse\WarehouseService;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AddWarehouse extends Component
{
    use WithPagination, WithFileUploads;

    #[Rule('required|min:5|unique:warehouses,warehouse_code')]
    public string $codeWarehouse;
    #[Rule('required|min:5|unique:warehouses,name')]
    public string $nameWarehouse;
    #[Rule('min:5')]
    public string $addressWarehouse = '';

    #[Rule([
        'areas' => 'array|required',
        'areas.*.area' => 'required|min:2|distinct',
        'areas.*.racks.*' => 'required|min:2|distinct',
    ])]
    public array $areas = [];

    public array $items = [];
    public bool $isShow = false;
    public bool $isShowModalNewItem = false;

    public bool $isAddedArea = false;
    public bool $notFound = false;
    public string $state;

    public string $area;
    public string $rack = '';
    #[Url(as: 'qId', keep: true)]
    public string $url = '';


    /**
     * fungsi ini digunakan untuk menambahkan input area baru
     * @return void
     */
    public function addArea()
    {
        $this->isAddedArea = true;
        $this->areas[] = [
            'area' => '',
            'racks' => ['']
        ];
    }

    /**
     * fungsi ini digunakan untuk menambahkan input rak baru ke area
     * @return void
     */
    public function addRack()
    {
        $this->areas[count($this->areas) - 1]['racks'][] = '';
    }

    /**
     * fungsi ini digunakan untuk menghapus area
     * @param $key
     * @return void
     */
    public function remove($key)
    {

        // hapus area terakhir dari array
        unset($this->areas[$key]);

        // pengecekan jika area kosong, maka sembunyikan tombol + rack
        if ($this->checkNotEmptyAreas()) {
            $this->isAddedArea = false;
        }
    }


    /**
     * fungsi ini digunakan untuk melakukan pengecekan apakah data array areas kosong
     * @return bool
     */
    private function checkNotEmptyAreas(): bool
    {
        return empty($this->areas);
    }

    /**
     * fungsi ini digunakan untuk menghapus rak diarea
     * @param $key
     * @param $subkey
     * @return void
     */
    public function removeRack($key, $subkey)
    {
        unset($this->areas[$key]['racks'][$subkey]);
    }


    public function handleAreaValidate()
    {
        $this->validate();
    }

    /**
     * fungsi ini digunakan untuk melakukan validasi data sebelum
     * menambah warehouse
     * @return void
     */
    public function validateInput()
    {
        $this->validate();

        $warehouseDto = new WarehouseDTO(
            $this->url,
            $this->codeWarehouse,
            $this->nameWarehouse,
            $this->areas,
            $this->addressWarehouse,
            ($this->state == 'outlet') ? WarehouseEnum::OUTLET : WarehouseEnum::CENTRAL
        );


        $repository = new WarehouseRepository();
        $service = new WarehouseService($repository);
        $result = $service->create($warehouseDto);
        $this->resetPage();
        $this->redirect("/warehouse/list-warehouse/view/{$result->id}", true);
        notify()->success('oke');

    }

    public function resetPage()
    {
        $this->reset('addressWarehouse');
        $this->reset('codeWarehouse');
        $this->reset('nameWarehouse');
        $this->reset('areas');
        $this->reset('isAddedArea');
    }


    public function mount()
    {
        try {

            $outlet = Outlet::find($this->url);
            $centralKitchen = CentralKitchen::find($this->url);

            if ($outlet == null && $centralKitchen == null) {
                $this->notFound = true;
                return;
            }

            if ($outlet != null) {
                $this->state = 'outlet';
            }

            if ($centralKitchen != null) {
                $this->state = 'central kitchen';
            }

            $this->notFound = false;

        } catch (Exception $exception) {
            Log::error('gagal mendapatkan id outlet / central kitchen di add warehouse');
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }
    }

    public function render()
    {

        return view('livewire.warehouse.add-warehouse');
    }


}
