<?php

namespace App\Livewire\Composition;

use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateItem extends Component
{
    use WithFileUploads;

    #[Rule('required|min:5|unique:items,code')]
    public string $code;
    #[Rule('required')]
    public string $name;
    public string $description = '';
    #[Rule('required')]
    public string $unit = '';

    #[Rule('numeric|min:0')]
    public string $minimumStock = '';
    #[Rule('required')]
    public string $category = '';
    public string $placement = '';
    #[Rule('required|min:0')]
    public string $route = 'BUY';
    #[Rule('numeric|min:0')]
    public string $inStock = '';
    #[Rule('exclude_if:inStock,""|required|min:0')]
    public string $avgCost = '';
    #[Rule('exclude_if:inStock,""|required|min:0')]
    public string $lastCost = '';

    public bool $isEmpty = false;
    public bool $isOutlet = true;
    #[Url(as: 'qId', keep: true)]
    public string $url = '';

    public Collection $allUnit;
    public Collection $allCategory;
    public array $warehousePlacement;

    public string $unitName;

    #[Validate('image|max:1024')] // 1MB Max
    public $thumbnail;

    public string $routeProduce = 'PRODUCECENTRALKITCHEN';
    private CompositionService $compositionService;

    public function mount()
    {
        $this->compositionService = app()->make(CompositionServiceImpl::class);
        $this->extractUrl();
        $this->getAllUnit();
        $this->getAllCategory();
        $this->getPlacement();

    }

    /**
     * extract url untuk dapatkan id outlet atau central kitchen
     * @return void
     * @throws BindingResolutionException
     */
    private function extractUrl()
    {
        Log::info('proses extract url id outlet / central kitchen');


        if ($this->url == '') {
            $this->isEmpty = true;
            return;
        }

        $outlet = $this->compositionService->findOutletById($this->url);


        if ($outlet != '') {
            $this->isOutlet = true;
            return;
        }


        $centralKitchen = $this->compositionService->findCentralKitchenById($this->url);


        if ($centralKitchen != '') {
            // found
            $this->isOutlet = false;

            return;
        }

        $this->isEmpty = true;
        return;

    }

    private function getAllUnit()
    {
        $this->allUnit = $this->compositionService->getAllUnit();

    }

    private function getAllCategory()
    {
        $this->allCategory = $this->compositionService->getAllCategory();

    }

    private function getPlacement()
    {
        if ($this->url != '') {
            $placement = $this->compositionService->getPlacement($this->url, $this->isOutlet);

            Log::debug($placement);


            if ($placement != null) {
                $this->warehousePlacement = $placement;
            }

        }
    }

    public function save()
    {

        $this->validate();
        $this->store();
    }

    private function store()
    {
        $this->compositionService = app()->make(CompositionServiceImpl::class);

        $this->validateRoute();
        $this->validateRouteProduce();
        $this->sanitizeInput();

        $result = null;

        if ($this->thumbnail != null) {
            $result = $this->thumbnail->store('public/item-image');
        }

        $item = $this->compositionService->saveItem(
            $this->route,
            $this->routeProduce,
            $this->inStock,
            $this->minimumStock,
            $this->thumbnail,
            $this->isOutlet,
            $this->placement,
            $this->code,
            $this->name,
            $this->unit,
            $this->description,
            $this->category,
            $this->url,
            $this->avgCost,
            $this->lastCost
        );

        if ($item == 'success') {
            notify()->success('Berhasil buat item', 'Sukses');
            $this->reset();
            return;
        }

        notify()->error('Gagal buat item', 'Gagal');
        $this->reset();
    }

    private function validateRoute()
    {
        if ($this->route != 'BUY' && $this->route != 'PRODUCE') {
            notify()->error('Rutediluar yang ditentukan', 'Gagal');
            abort(422, 'Invalid route.');
        }
    }

    private function validateRouteProduce()
    {
        if ($this->routeProduce != 'PRODUCEOUTLET' && $this->routeProduce != 'PRODUCECENTRALKITCHEN') {
            Log::info($this->routeProduce);
            notify()->error('Rute produksi diluar yang ditentukan', 'Gagal');
            abort(422, 'Invalid production route.');
        }
    }

    private function sanitizeInput()
    {
        $this->inStock = $this->inStock ?: '0';
        $this->minimumStock = $this->minimumStock ?: '0';
        $this->avgCost = $this->avgCost ?: '0';
        $this->lastCost = $this->lastCost ?: '0';
    }


    public function updateUnitName()
    {

        $selectedUnit = $this->allUnit->firstWhere('id', $this->unit);

        if ($selectedUnit) {
            $this->unitName = $selectedUnit->name;
        } else {
            $this->unitName = null;
        }
    }

    public function render()
    {
        return view('livewire.composition.create-item');
    }
}
