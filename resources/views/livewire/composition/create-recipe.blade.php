@php use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ $type == 'recipeMenu' ? 'Buat resep menu' : 'Buat resep bahan 1/2 jadi' }}
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-secondary btn-nav margin-left-10"
                                @click="$dispatch('cancel-edit-warehouse')">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="save"
                    >Simpan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">
        <x-notify::notify/>
        <div class="row">


            @if($type == 'recipeMenu')
                {{-- Tambah kategori baru --}}
                <div class="col-sm-4 offset-1">
                    {{-- Kode kategori --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode resep</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="RBSJ0001"
                               wire:model.live.debounce.600ms="code">
                        @error('code') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>


                    {{-- PILIH MENU --}}
                    <div class="margin-top-20">
                        <div class="container-input-default">
                            <label for="description" class="form-label">Pilih menu</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <select class="form-select input-default" wire:model="selectMenuOrMaterial"
                            >
                                <option value="" selected disabled>Pilih menu</option>
                            </select>
                        </div>
                    </div>

                    {{-- PILIH VARIASI --}}
                    <div class="margin-top-20">
                        <div class="container-input-default">
                            <label for="description" class="form-label">Pilih variasi menu</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            {{-- TODO: Perbaiki wire model variasi dibawah ini --}}
                            <select class="form-select input-default" wire:model="selectMenuOrMaterial"
                            >
                                <option value="" selected disabled>Pilih variasi menu</option>
                            </select>
                        </div>
                    </div>
                </div>
            @else
                {{-- Tambah kategori baru --}}
                <div class="col-sm-4 offset-1">
                    {{-- Kode kategori --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode resep</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="RBSJ0001"
                               wire:model.live.debounce.600ms="code">
                        @error('code') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>


                    {{-- PILIH MENU --}}
                    <div class="margin-top-20">
                        <div class="container-input-default">
                            <label for="description" class="form-label">Pilih bahan</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <select class="form-select input-default" wire:model="selectMenuOrMaterial">
                                <option value="" selected disabled>Pilih bahan</option>

                                @if(!empty($menuOrMaterial))

                                    @foreach($menuOrMaterial as $component)
                                        <option value="{{ $component->id }}">{{ $component->name }}</option>
                                    @endforeach

                                @endif

                            </select>
                            @error('selectMenuOrMaterial') <span text-xs text-red-600>{{ $message }}</span> @enderror
                        </div>
                    </div>

                </div>
            @endif
        </div>

        <div class="row margin-top-32">
            <div class="col-sm-8 offset-1">
                {{-- RESEP --}}
                <div>
                    <label class="form-label input-label">Resep</label>

                    <div id="divider" class="margin-top-8"></div>
                    @error('ingredients') <span text-xs text-red-600>{{ $message }}</span> @enderror
                    @error('ingredients.*') <span text-xs text-red-600>{{ $message }}</span> @enderror
                    <div class="margin-top-8">
                        <table class="table table-bordered only-underline table-create-recipes body-text-regular">
                            <thead>
                            <tr class="no-border-side">
                                <th scope="col">Ingredients</th>
                                <th scope="col">Usage</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Avg Cost</th>
                                <th scope="col">Last Cost</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($ingredients as $key => $ingredient)
                                <tr class="row-recipe" wire:key="{{ $ingredient['id'] }}">
                                    <td>

                                        <select class="form-select create-recipes-select"
                                                wire:model="ingredients.{{ $key }}.id"
                                                wire:change="itemSelected('{{ $key }}', $event.target.value)"
                                        >
                                            <option disabled selected value="">Pilih ingredients</option>

                                            @if($items != null)
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}"
                                                            data-name="{{ $item->name }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>


                                    </td>

                                    <td>
                                        <input type="number" class="form-control no-border"
                                               id="exampleFormControlInput1"
                                               wire:model="ingredients.{{ $key }}.usage"
                                               wire:change="updateUsage('{{ $key }}')">
                                    </td>

                                    <td class="disabled col-avg-cost">
                                        <input type="text" disabled class="form-control input-disabled-recipes"
                                               id="avgCost" wire:model="ingredients.{{ $key }}.unit.name"
                                        >
                                    </td>


                                    <td class="disabled col-avg-cost">
                                        <input type="text" disabled class="form-control input-disabled-recipes"
                                               id="avgCost" wire:model="ingredients.{{$key}}.avgCost">
                                    </td>
                                    <td class="disabled col-last-cost">
                                        <input type="text" disabled class="form-control input-disabled-recipes"
                                               id="lastCost" wire:model="ingredients.{{$key}}.lastCost">
                                    </td>
                                    <td class="delete-recipes">
                                        <i class="x-icon" onclick="deleteRow(this)"></i>
                                    </td>
                                </tr>

                            @endforeach


                            <tr id="divideContent">
                                <td colspan="6" class="span-all-columns"
                                    style="padding-bottom: 5px; padding-top: 5px;">
                                    <button class="btn icon-text" type="button" id="addIngredients"
                                            wire:click="addIngredient">
                                        + Add ingredients
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="span-all-columns"
                                    style="padding-bottom: 10px; border-right: none;">
                                    <div>Total Average & Last Cost</div>
                                </td>
                                <td colspan="1" style="border-right: none;">
                                    <p class="body-text-bold" id="totalAvgCost">{{ $totalAvg }}</p>
                                </td>
                                <td colspan="1" style="border-right: none;">
                                    <p class="body-text-bold" id="totalLastCost">{{ $totalLastCost }}</p>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-page-layout>

@section('footer-script')

@endsection

