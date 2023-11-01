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
//
//        Log::info($this->items);
    }

    #[On('load-modal-rack')]
    public function loadRack($area, $rack)
    {
        $this->area = $area;
        $this->rack = $rack;

        Log::info($this->area);

        // buka modal
        $this->openModal();

        // proses rack

    }

    #[On('dismiss-modal')]
    public function dismissModal()
    {
        
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
        Log::info($this->areas);
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
