<?php

namespace App\Livewire\Composition;

use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateItem extends Component
{

    #[Rule('required|min:5|unique:items,code')]
    public string $code;
    #[Rule('required')]
    public string $name;
    public string $description;
    #[Rule('required')]
    public string $unit = '';
    #[Rule('required|numeric|min:0')]
    public string $inStock;
    #[Rule('required|numeric|min:0')]
    public string $minimumStock;
    #[Rule('required')]
    public string $category = '';
    public string $placement = '';
    #[Rule('required|min:0')]
    public string $route;

    public bool $isEmpty = false;
    public bool $isOutlet = true;
    #[Url(as: 'qId', keep: true)]
    public string $url = '';

    public Collection $allUnit;
    public Collection $allCategory;
    public array $warehousePlacement;

    public string $unitName;


    // ambil id url
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

        if ($outlet != null) {
            // found
            return;
        }

        $centralKitchen = $this->compositionService->findCentralKitchenById($this->url);

        if ($centralKitchen != null) {
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
            $this->warehousePlacement = $this->compositionService->getPlacement($this->url, $this->isOutlet);
        }
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
