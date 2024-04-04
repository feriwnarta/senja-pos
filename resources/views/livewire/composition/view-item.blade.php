@php use App\Models\CentralKitchen;use App\Models\Outlet;use Illuminate\Support\Facades\Storage; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail Item
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10">Edit
                    </button>
                    

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>

        </div>
    </x-slot>
    <div id="content-loaded">
        <div class="row">
            <div class="col-sm-4 offset-1">
                {{-- KODE ITEM --}}
                <div class="container-input-default">
                    <label for="warehouseInput"
                           class="form-label input-label">Kode item</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="KU004"
                           disabled
                           value="{{ $item->code }}"
                    >

                </div>

                {{-- NAMA ITEM --}}
                <div class="container-input-default margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Nama item</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Daging ayam"
                           disabled
                           value="{{ $item->name }}"
                    >
                </div>


                {{-- DESKRIPSI --}}
                <div class="margin-top-20">
                    <label for="description" class="form-label">Deskripsi item</label>
                    <div id="divider" class="margin-symmetric-vertical-6"></div>
                    <textarea class="form-control textarea" id="description" rows="5"
                              placeholder="{{ $item->description }}"
                              disabled
                    ></textarea>
                </div>

                {{-- Unit --}}
                <div class="margin-top-20">
                    <div class="container-input-default">
                        <label for="description" class="form-label">Unit</label>
                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                        <select class="form-select input-default" wire:model="unit"
                                disabled>
                            <option value="" selected disabled>{{ $item->unit->name }}</option>

                        </select>
                    </div>
                </div>

                {{-- Rute --}}
                <div class="margin-top-20">
                    <label for="description" class="form-label">Rute</label>
                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"
                               value="BUY" wire:model.live="route"
                               disabled {{ ($item->route == 'BUY' ? 'checked' : '') }}>
                        <label class="form-check-label" for="exampleRadios1">
                            Dapat dibeli
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio"
                               value="PRODUCE" wire:model.live="route"
                               disabled {{ ($item->route == 'PRODUCE' ? 'checked' : '') }}>
                        <label class="form-check-label" for="exampleRadios2">
                            Dapat diproduksi
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 offset-1">
                <div class="d-flex flex-column align-self-center align-items-center">
                    <img width="221" height="221" src="{{ asset($item->thumbnail) }}" alt="" srcset="">
                </div>
            </div>
        </div>
    </div>

</x-page-layout>

