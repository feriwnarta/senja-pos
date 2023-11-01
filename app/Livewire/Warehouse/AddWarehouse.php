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

    public function addArea()
    {
        $this->isAddedArea = true;

        $this->areas[] = ['area' => ['', '', '', '']];
    }

    #[On('dismiss-modal')]
    public function dismissModal()
    {
        $this->isShow = false;
    }


    public function addRack()
    {
        $this->areas[count($this->areas) - 1]['rack'][] = ['', '', ''];
    }

    public function remove($key)
    {
        unset($this->areas[$key]);
    }

    public function removeRack($key, $subkey)
    {
        unset($this->areas[$key]['rack'][$subkey]);
    }

    #[On('load-modal')]
    public function loadItem()
    {

        $this->isShow = !$this->isShow;
        $item = Item::orderBy('id')->cursorPaginate(20)->toArray();
        $this->items['data'] = $item['data'];
        $this->nextCursorId = $item['next_cursor'];

        Log::info($this->items);

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

    public function addCheckbox($item)
    {
        $this->js("console.log('$item')");
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
