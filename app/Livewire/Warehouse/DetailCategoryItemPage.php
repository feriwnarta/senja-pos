<?php

namespace App\Livewire\Warehouse;

use Livewire\Attributes\Url;
use Livewire\Component;

// TODO: LANJUTKAN VIEW CATEGORY DAN EDIT
class DetailCategoryItemPage extends Component
{
    #[Url(as: 'q')]
    public string $urlQuery = '';
    public string $categoryId;

    public function placeholder()
    {
        return <<<'HTML'
        <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.warehouse.detail-category-item-page');
    }

    public function mount()
    {

        $this->extractUrl();
    }

    private function extractUrl()
    {
        // jika url kosong atau null, maka redirect ke warehouse list
        if ($this->urlQuery == '' || $this->urlQuery == null) {
            $this->redirect('/warehouse/category-item/', true);
        }


        // Mencari nilai parameter "mode" menggunakan preg_match
        if (preg_match('/^([^&]+)&mode=([^&]+)/', $this->urlQuery, $matches)) {
            $id = $matches[1];
            $modeValue = $matches[2];

            // set id
            $this->categoryId = $id;

            // ubah modenya menjadi edit
            if ($modeValue == 'edit') {
                $this->dispatch('edit-unit');
            }

        } else {
            $this->categoryId = $this->urlQuery;
        }

    }
}
