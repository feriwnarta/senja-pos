<?php

namespace App\Livewire\Warehouse;

use App\Dto\WarehouseDTO;
use App\Dto\WarehouseEnum;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\CentralKitchen;
use App\Models\Item;
use App\Models\Outlet;
use App\Models\Warehouse;
use App\Repository\Warehuouse\WarehouseRepository;
use App\Service\Warehouse\WarehouseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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
    #[Rule('required|min:5')]
    public string $addressWarehouse;

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

    protected $rules = [
        'areas.*.area.area' => 'required|min:2',
        'areas.*.area.rack' => 'required|min:2',
        'areas.*.area.category_inventory' => 'required|min:3',
        'areas.*.rack.*.rack' => 'required|min:2',
        'areas.*.rack.*.category_inventory' => 'required|min:3',

    ];
    protected $messages = [
        'areas.*.area.area.required' => 'The Area A field is required.',
        'areas.*.area.area.min' => 'The Area A field should be at least 3 characters.',
        'areas.*.area.rack.required' => 'The Rack field is required.',
        'areas.*.area.rack.min' => 'The Rack field should be at least 3 characters.',
        'areas.*.area.category_inventory.required' => 'The Category Inventory field is required.',
        'areas.*.area.category_inventory.min' => 'The Category Inventory field should be at least 3 characters.',
        'areas.*.rack.*.rack.required' => 'The Rack field is required.',
        'areas.*.rack.*.rack.min' => 'The Rack field should be at least 3 characters.',
        'areas.*.rack.*.category_inventory.required' => 'The Category Inventory field is required.',
        'areas.*.rack.*.category_inventory.min' => 'The Category Inventory field should be at least 3 characters.',
    ];

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



    /**
     * fungsi ini digunakan untuk melakukan validasi data sebelum
     * menambah warehouse
     * @return void
     */
    public function validateInput()
    {

        // TODO: Perbaiki validasi message rack yang sama untuk satu gudang atau area yg sama
        // lakukan validasi hanya data yang diperlukan
        $this->validate([
            'areas' => 'array',  // Validate areas as a required array
            'areas.*.area' => 'required|min:2|distinct',  // Validate area name
            'areas.*.racks.*' => 'required|min:2|distinct', // Validate racks as a required array with distinct values
            'codeWarehouse' => 'required|min:5',
            'nameWarehouse' => 'required|min:5',
            'addressWarehouse' => 'min:5',
        ]);

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


        $this->js("alert('sukses buat warehouse')");

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
