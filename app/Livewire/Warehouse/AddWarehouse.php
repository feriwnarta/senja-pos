<?php

namespace App\Livewire\Warehouse;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AddWarehouse extends Component
{
    use WithPagination, WithFileUploads;

    #[Rule('required|min:5')]
    public string $codeWarehouse;
    #[Rule('required|min:5')]
    public string $nameWarehouse;

    public array $areas = [];

    public array $items;
    public bool $isShow = false;
    public bool $isShowModalNewItem = false;

    public bool $isAddedArea = false;
    public string $nextCursorId;

    public string $area;
    public string $rack = '';

    public string $codeItem;
    public string $nameItem;
    public string $category;
    public string $descriptiom;

    #[Rule('image|max:1024')] // 1MB Max
    public $photoNewItem;

    protected $rules = [
        'areas.*.area.area' => 'required|min:3',
        'areas.*.area.rack' => 'required|min:3',
        'areas.*.area.category_inventory' => 'required|min:3',
        'areas.*.rack.*.rack' => 'required|min:3',
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

    public function upload()
    {
        $this->validate([
            'photo' => [
                'image',
                Rule::dimensions()->maxWidth(1024)->maxHeight(1024),
                Rule::size(1024 * 1024)->message('Harap unggah berkas yang lebih kecil dari 1MB.'), // Custom message
            ],
        ]);

        // Perform file storage or any other action as needed.
    }

    /**
     * fungsi ini digunakan untuk menambahkan input area baru
     * @return void
     */
    public function addArea()
    {
        $this->isAddedArea = true;
        $this->areas[] = ['area' => ['area' => '', 'rack' => '', 'category_inventory' =>
            '', 'item' => []]];
    }

    /**
     * fungsi ini digunakan untuk menambahkan input rak baru ke area
     * @return void
     */
    public function addRack()
    {
        $this->areas[count($this->areas) - 1]['rack'][] = ['rack' => '', 'category_inventory' => '', 'item' => []];
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
        unset($this->areas[$key]['rack'][$subkey]);
    }

    /**
     * listener dari file native javacript ini digunakan untuk melakukan pembukaan
     * modal secara manual dengan memanggil fungsi open modal
     * @param $area
     * @return void
     */
    #[On('load-modal')]
    public function loadItem($area)
    {
        $this->area = $area;
        Log::info($this->area);
        $this->openModal();

    }

    /**
     * fungsi ini digunakan untuk melakukan pembukaan modal
     * @return void
     */
    private function openModal()
    {
        $this->isShow = !$this->isShow;

        // lakuakn cursor paginate data item sebanyak 20
        $item = Item::orderBy('id')->cursorPaginate(20)->toArray();
        // simpan data cursor ke global $items
        $this->items['data'] = $item['data'];
        // simpan id next cursor ke global next cusor id
        // cursor id ini digunakan untuk mendapatkan data selanjutnya menggunakan id
        $this->nextCursorId = $item['next_cursor'];


    }

    /**
     * fungsi terpisah untuk melakukan pembukaan modal dari data rack,
     * dikarenakan pada tahap ini terdapat perbedaan logic dari pengolahan
     * modal yang lainnya
     * @param $area
     * @param $rack
     * @return void
     */
    #[On('load-modal-rack')]
    public function loadRack($area, $rack)
    {
        // simpan area
        $this->area = $area;
        // simpan rack
        $this->rack = $rack;
        // buka modal
        $this->openModal();
    }

    /**
     * listener ini digunakan untuk melakuakn penutupan modal yang terbuka secara manual
     * dan melakukan logic tambahan untuk mengosongkan data item cursor yang keload
     * @return void
     */
    #[On('dismiss-modal')]
    public function dismissModal()
    {
        $this->items = [];
        $this->isShow = false;
    }

    /**
     * listener ini digunakan untuk mendapatkan data item lebih dari data sebelumnya (infinite loading)
     * menggunakan cursor dengan cursor id
     * @return void
     */
    #[On('load-more')]
    public function handleScroll()
    {
        // cek terlebih dahulu apakah cursor id tidak null
        // jika datanya null berarti sudah tidak ada data lagi
        if ($this->nextCursorId != null) {
            $nextItems = Item::orderBy('id')->cursorPaginate(20, ['*'], 'cursor', $this->nextCursorId)->toArray();

            // tambahkan data baru ke variabel $items
            foreach ($nextItems['data'] as $nextItem) {
                $this->items['data'][] = $nextItem;
            }

            // simpan next cursor id dari cursor ini
            $this->nextCursorId = $nextItems['next_cursor'];
        }
    }


    /**
     * fungsi ini digunakan untuk melakukan penyimpanan gudang baru
     * @return void
     */
    public function saveWarehouse()
    {
        // lakukan validasi
        $this->validate();
    }


    /**
     * fungsi ini digunakan untuk menambahkan item baru ke area dan rak
     * @param $id
     * @param $name
     * @return void
     */
    public function addItem($id, $name)
    {

        $isExist = false;

        // cek apakah data sudah ditambahkan diarea
//        foreach ($this->areas as $dataArea) {
//            Log::info(json_encode($dataArea));
//
//            // cek apakah item berada didalam area
//            $isExist = $this->checkExistItem($dataArea['area']['item'], $name);
//
//            // jika item sudah ditambahkan maka hentikan looping
//            if ($isExist) {
//                return;
//            }
//
//            // cek data di dalam area rack
//            if (isset($dataArea['rack'])) {
//                foreach ($dataArea['rack'] as $subRack) {
//                    $isExist = $this->checkExistItem($subRack['item'], $name);
//
//                    // jika item sudah ditambahkan didalam rack area maka hentikan looping
//                    if ($isExist) {
//                        return;
//                    }
//                }
//            }
//
//        }

        if ($this->rack == '') {

            // tambahkan item ke area yang sudah dipilih
            $this->areas[$this->area]['area']['item'][] = [
                'id' => $id,
                'name' => $name,
            ];

            Log::info($this->areas);
            return;
        }


        $this->areas[$this->area]['rack'][$this->rack]['item'][] = [
            'id' => $id,
            'name' => $name,
        ];

    }

    public function mount()
    {
        $this->dispatch('load-add-warehouse-script');
    }

    public function render()
    {
        return view('livewire.warehouse.add-warehouse');
    }

    public function openModalNewItem()
    {
        $this->isShowModalNewItem = true;
    }

    public function closeModalNewItem()
    {
        $this->isShowModalNewItem = false;
    }

    /**
     * fungsi ini digunakan untuk mengecek apakah item sudah pernah ditambahkan
     * @return void
     */
    private function checkExistItem(array $areas, string $name): bool
    {
        foreach ($areas as $dataItem) {
            if ($dataItem['name'] == $name) {
                $this->dispatch('reject-checkbox', $name);
                return true;
            }
        }

        return false;
    }


}
