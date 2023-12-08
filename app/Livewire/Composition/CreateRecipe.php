<?php

namespace App\Livewire\Composition;

use App\Service\Impl\RecipeServiceImpl;
use App\Service\RecipeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreateRecipe extends Component
{
    #[Url(as: 'type', keep: true)]
    public string $type = '';
    public string $code;
    public array $ingredients;
    public Collection $items;
    public string $selectItem = '';
    private RecipeService $recipeService;

    public function mount()
    {
        $this->recipeService = app()->make(RecipeServiceImpl::class);
        $result = $this->extractUrl($this->type);
        $this->type = $result;
        $this->getAllItem();

    }


    public function extractUrl(string $url)
    {
        if ($url != 'recipeMenu' && $url != 'recipeSemi') {
            return 'recipeMenu';
        }

        return $url;
    }

    private function getAllItem()
    {
        $items = $this->recipeService->getAllItem();

        // item null karena error
        if ($items == null) {
            return;
        }

        $this->items = $items;
    }

    public function render()
    {
        return view('livewire.composition.create-recipe');
    }

    // dapatkan data item

    /**
     * lakukan penambahan array ingredients
     * @return void
     */
    public function addIngredient()
    {
        $this->ingredients[] = [
            'id' => '',
            'usage' => '',
            'unit' => [
                'id' => '',
                'name' => '',
            ],
            'avgCost' => '',
            'lastCost' => '',
        ];
    }

    public function itemSelected($index)
    {

        $itemId = $this->ingredients[$index]['id'];
        $result = $this->items->find($itemId);

        // jika error maka hentikan program
        if ($result == null && empty($result)) {
            Log::error('gagal mendapatkan data item yang dipilih saat buat resep setengah jadi');
            return;
        }

        $this->ingredients[$index]['unit']['id'] = $result->unit->id;
        $this->ingredients[$index]['unit']['name'] = $result->unit->name;


        Log::debug($this->ingredients);

    }

}
