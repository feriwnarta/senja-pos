<?php

namespace App\Livewire\Warehouse;

use App\Service\CategoryItemService;
use App\Service\Impl\CategoryItemServiceImpl;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class AddCategory extends Component
{

    public array $items;
    public array $selectedItem;
    public ?string $nextCursor = null;
    public bool $isSave = false;
    public Collection $units;
    public string $unitSelected;
    public ?string $search = null;
    private array $unit;
    private CategoryItemService $categoryService;

    public function boot()
    {
        $this->categoryService = app()->make(CategoryItemServiceImpl::class);

    }


    public function rendered()
    {

        $this->dispatch('set-width-title');
        $this->dispatch('update-menu');
        $this->dispatch('load-script');

    }

    public function render()
    {
        // cari
        $units = $this->searchUnit($this->search);

        if (!empty($units)) {
            $this->units = $units;
        }
        return view('livewire.warehouse.add-category');
    }

    private function searchUnit($unit)
    {
        try {
            if ($unit) {
                // Jika $unit tidak null (nama unit diberikan), lakukan pencarian
                return \App\Models\Unit::where('name', 'like', "%{$unit}%")->get();
            } else {
                // Jika $unit null (nama unit tidak diberikan), tampilkan semua unit
                return \App\Models\Unit::all();
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return [];
    }

    public function loadUnit()
    {
        try {
            $this->units = \App\Models\Unit::get();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function setUnit(string $id, string $name)
    {
        if ($id != null && $name != null) {
            $this->unit = [
                'id' => $id,
                'name' => $name
            ];

            $this->unitSelected = $name;
        }

    }

    #[On('load-item')]
    public function loadItem()
    {

        try {

            $id = [];

            foreach ($this->selectedItem as $select) {
                $id[] = $select['id'];
            }

            Log::debug($id);

            $items = $this->categoryService->getItemCursor($id);


            if (!empty($items['data'])) {
                $this->items = $items['data'];
            }


            $this->nextCursor = $items['next_cursor'];
        } catch (Exception $exception) {
            Log::error('adaerror');
        }
    }

    #[On('load-more')]
    public function loadMore()
    {


        if ($this->nextCursor != null) {
            $items = $this->categoryService->getItemNextCursor(nextCursor: $this->nextCursor);

            if (!empty($items['data'])) {
                foreach ($items['data'] as $item) {
                    $this->items[] = $item;
                }
            }

        }
        $this->dispatch('stop-request');

    }

    /**
     * fungsi ini akan dijalankan saat checkbox diklik atau tidak diklik
     * lakukan pencarian di array selected item, jika id sudah ada maka hapus, jika belum maka tambahkan
     * @param string $id
     * @param string $name
     * @return void
     */
    public function selectItem(string $id, string $name)
    {
        if ($id != null && $name != null) {

            if (count($this->selectedItem) < 1) {
                $this->selectedItem[] = [
                    'id' => $id,
                    'name' => $name
                ];


                return;
            }

            $found = false;

            // cari apakah id sudah ditambahkan di selected item
            foreach ($this->selectedItem as $key => $select) {
                if ($select['id'] == $id) {
                    unset($this->selectedItem[$key]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Hanya menambahkan elemen baru jika tidak ditemukan
                $this->selectedItem[] = [
                    'id' => $id,
                    'name' => $name
                ];
            }

            // Opsi: Reset array keys untuk memastikan konsistensi indeks
            $this->selectedItem = array_values($this->selectedItem);


        }

    }

    /**
     * hapus checkbox yang dipilih
     * @param string $id
     * @return void
     */
    public function unSelect(string $id)
    {

        if ($id !== null) {

            // cari apakah id sudah ditambahkan di selected item
            foreach ($this->selectedItem as $key => $select) {
                if ($select['id'] == $id) {
                    unset($this->selectedItem[$key]);
                    break;
                }
            }
        }

    }

    public function saveSelectedItem()
    {
        $this->isSave = true;
        Log::info($this->selectedItem);
        $this->items = [];
    }


}
