<?php

namespace App\Livewire\Warehouse;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class DetailUnitPage extends Component
{

    #[Url(as: 'q')]
    public string $urlQuery = '';
    public string $unitId;
    public \App\Models\Unit $unit;
    public string $mode = 'view';

    #[Rule("required|min:5|unique:units,code")]
    public string $code;
    #[Rule('required|min:1|unique:units,name')]
    public string $name;
    public bool $notFound = false;


    public function mount()
    {
        $this->extractUrl();

        // ambil data unit berdasarkan id
        $unit = $this->getUnitById($this->unitId);

        if ($unit == null) {
            $this->notFound = true;
            return;
        }

        $this->unit = $unit;
        $this->code = $this->unit->code;
        $this->name = $this->unit->name;
    }

    private function extractUrl()
    {
        // jika url kosong atau null, maka redirect ke warehouse list
        if ($this->urlQuery == '' || $this->urlQuery == null) {
            $this->redirect('/warehouse/list-warehouse/', true);
        }


        // Mencari nilai parameter "mode" menggunakan preg_match
        if (preg_match('/^([^&]+)&mode=([^&]+)/', $this->urlQuery, $matches)) {
            $id = $matches[1];
            $modeValue = $matches[2];

            // set id
            $this->unitId = $id;

            // ubah modenya menjadi edit
            if ($modeValue == 'edit') {
                $this->dispatch('edit-unit');
            }

        } else {
            $this->unitId = $this->urlQuery;
        }

    }

    private function getUnitById(string $id)
    {
        try {
            return \App\Models\Unit::find($id);

        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    #[On('edit-unit')]
    public function editUnit()
    {

        $this->mode = 'edit';
        $this->urlQuery = "{$this->unitId}&mode=edit";

    }


    #[On('cancel-edit')]
    public function cancelEdit()
    {
        $this->mode = 'view';
        $this->urlQuery = "{$this->unitId}";
    }

    #[On('save-edit')]
    public function save()
    {
        $this->validate(
            [
                'code' => "required|min:5|unique:units,code,{$this->unitId}",
                'name' => "required|min:1|unique:units,name,{$this->unitId}"
            ]
        );


        $this->storeData($this->unitId, $this->code, $this->name);
    }

    private function storeData(string $id, string $code, string $name)
    {

        try {
            DB::beginTransaction();

            $unit = \App\Models\Unit::find($id)->update(['code' => $code, 'name' => $name]);

            DB::commit();

            if ($unit) {
                $this->js("alert('berhasil edit unit')");
                $this->dispatch('cancel-edit');
                return;
            }
            $this->js("alert('gagal edit unit')");
        } catch (Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            $this->js("alert('gagal edit unit')");
        }
    }

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
        return view('livewire.warehouse.detail-unit-page');
    }
}
