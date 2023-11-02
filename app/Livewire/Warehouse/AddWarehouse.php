<?php

namespace App\Livewire\Warehouse;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AddWarehouse extends Component
{
    use WithPagination;

    #[Rule('required|min:5')]
    public string $codeWarehouse;
    #[Rule('required|min:5')]
    public string $nameWarehouse;

    public array $areas = [];

    public array $items;
    public bool $isShow = false;
    public bool $isAddedArea = false;
    public string $nextCursorId;

    public string $area;
    public string $rack = '';

    public function addArea()
    {
        $this->isAddedArea = true;
        $this->areas[] = ['area' => ['area' => '', 'rack' => '', 'category_inventory' =>
            '', 'item' => []]];
    }

    public function addRack()
    {
        $this->areas[count($this->areas) - 1]['rack'][] = ['rack' => '', 'category_inventory' => '', 'item' => []];
    }

    public function remove($key)
    {
        unset($this->areas[$key]);
    }

    public function test()
    {
        dd($this->areas);
    }

    public function removeRack($key, $subkey)
    {
        unset($this->areas[$key]['rack'][$subkey]);
    }

    #[On('load-modal')]
    public function loadItem($area)
    {
        $this->area = $area;
        Log::info($this->area);
        $this->openModal();

    }

    private function openModal()
    {

        $this->isShow = !$this->isShow;
        $item = Item::orderBy('id')->cursorPaginate(20)->toArray();
        $this->items['data'] = $item['data'];
        $this->nextCursorId = $item['next_cursor'];


    }


    #[On('load-modal-rack')]
    public function loadRack($area, $rack)
    {
        $this->area = $area;
        $this->rack = $rack;

        // buka modal
        $this->openModal();

    }

    #[On('dismiss-modal')]
    public function dismissModal()
    {
        $this->items = [];
        $this->isShow = false;
    }


    #[On('load-more')]
    public function handleScroll()
    {

        if ($this->nextCursorId != null) {
            $nextItems = Item::orderBy('id')->cursorPaginate(20, ['*'], 'cursor', $this->nextCursorId)->toArray();

            foreach ($nextItems['data'] as $nextItem) {
                $this->items['data'][] = $nextItem;
            }

            $this->nextCursorId = $nextItems['next_cursor'];
        }

    }

    public function addItem($id, $name)
    {

        $isExist = false;

        // cek apakah data sudah ditambahkan diarea
        foreach ($this->areas as $dataArea) {
            Log::info(json_encode($dataArea));

            $isExist = $this->checkExistItem($dataArea['area']['item'], $name);

            if ($isExist) {
                return;
            }

            // cek data di dalam area rack
            if (isset($dataArea['rack'])) {
                foreach ($dataArea['rack'] as $subRack) {
                    $isExist = $this->checkExistItem($subRack['item'], $name);
                    if ($isExist) {
                        return;
                    }
                }
            }

        }

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

    /**
     * fungsi ini digunakan untuk mengecek apakah item sudah pernah ditambahkan
     * @return void
     */
    private function checkExistItem(array $areas, string $name): bool
    {
        foreach ($areas as $dataItem) {
            if ($dataItem['name'] == $name) {
                $this->dispatch('reject-checkbox');
                return true;
            }
        }

        return false;
    }

    public function mount()
    {
        $this->dispatch('load-add-warehouse-script');
    }

    public function render()
    {
        return view('livewire.warehouse.add-warehouse');
    }


}
