<?php

namespace App\Livewire\Composition;

use App\Service\CompositionService;
use App\Service\Impl\CompositionServiceImpl;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateItem extends Component
{

    public string $code;
    public string $name;
    public string $description;
    public string $unit;
    public string $inStock;
    public string $minimumStock;
    public string $category;
    public string $placement;
    public string $route;

    public bool $isEmpty = false;
    public bool $isOutlet = true;
    #[Url(as: 'qId', keep: true)]
    public string $url = '';


    // ambil id url
    private CompositionService $compositionService;

    public function mount()
    {

        $this->extractUrl();
    }


    /**
     * extract url untuk dapatkan id outlet atau central kitchen
     * @return void
     * @throws BindingResolutionException
     */
    private function extractUrl()
    {
        Log::info('proses extract url id outlet / central kitchen');
        $this->compositionService = app()->make(CompositionServiceImpl::class);


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

    }

    public function render()
    {
        return view('livewire.composition.create-item');
    }
}
