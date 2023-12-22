@php use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>
    <x-notify::notify/>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat item
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-secondary btn-nav margin-left-10"
                                @click="$dispatch('cancel-edit-warehouse')" wire:loading.attr="disabled">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="save"
                            wire:loading.attr="disabled"
                    >Simpan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>


        <div id="content-loaded">
            <div class="row">

                @if($isEmpty)
                    <h1 class="subtitle-3-medium">Outlet / central kitchen tidak ditemukan pastikan anda sudah
                        memilih outlet / central kitchen sebelum membuat item</h1>
                @else

                    <div class="col-sm-4 offset-1">
                        {{-- KODE ITEM --}}
                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">Kode item</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="KU004"
                                   wire:model.live.debounce.600ms="code">
                            @error('code') <span text-xs text-red-600>{{ $message }}</span> @enderror

                        </div>

                        {{-- NAMA ITEM --}}
                        <div class="container-input-default margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Nama item</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="Daging ayam"
                                   wire:model.live.debounce.600ms="name">
                            @error('name') <span text-xs text-red-600>{{ $message }}</span> @enderror

                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="margin-top-20">
                            <label for="description" class="form-label">Deskripsi item</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="description" rows="5"
                                      placeholder="Daging sapi ayam segar"
                                      wire:model.blur="description"></textarea>
                        </div>

                        {{-- Unit --}}
                        <div class="margin-top-20">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Unit</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <select class="form-select input-default" wire:model="unit"
                                        wire:change="updateUnitName">
                                    <option value="" selected disabled>Pilih unit</option>
                                    @if(!empty($allUnit))
                                        @foreach($allUnit as $unit)
                                            <option value="{{ $unit->id }}">
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('unit') <span text-xs text-red-600>{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="margin-top-20">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="warehouseInput" class="form-label input-label">Stok saat ini</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="number" class="form-control input-default" id="warehouseInput"
                                               placeholder="0" wire:model.live.debounce.600ms="inStock">
                                        @error('inStock') <span text-xs text-red-600>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="description" class="form-label">Unit</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="text" class="form-control input-default" disabled
                                               wire:model="unitName">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="margin-top-20">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="warehouseInput" class="form-label input-label">Stok minimum</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="number" class="form-control input-default" id="warehouseInput"
                                               placeholder="0" wire:model.live.debounce.600ms="minimumStock">
                                        @error('minimumStock') <span text-xs
                                                                     text-red-600>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="description" class="form-label">Unit</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="text" class="form-control input-default" disabled
                                               wire:model="unitName">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="margin-top-20" x-show="$wire.inStock != ''">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="warehouseInput" class="form-label input-label">Harga
                                            rata-rata</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="text" class="form-control input-default" id="warehouseInput"
                                               x-mask:dynamic="$money($input, ',')"
                                               placeholder="12.000" wire:model.live.debounce.600ms="avgCost">
                                        @error('avgCost') <span text-xs text-red-600>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="warehouseInput" class="form-label input-label">Harga akhir</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <input type="text" class="form-control input-default" id="warehouseInput"
                                               x-mask:dynamic="$money($input, ',')"
                                               placeholder="12.000" wire:model.live.debounce.600ms="lastCost">
                                        @error('lastCost') <span text-xs text-red-600>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="margin-top-20">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Kategori item</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <select class="form-select input-default"
                                        wire:model="category">
                                    <option value="" disabled selected>Pilih kategori item</option>


                                    @if(isset($allCategory))
                                        @foreach($allCategory as $category)

                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>

                                        @endforeach
                                    @endif

                                </select>
                                @error('category') <span text-xs text-red-600>{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="margin-top-20">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Penempatan</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <select class="form-select input-default"
                                        wire:model="placement">
                                    <option value="" disabled selected>Pilih area dan rak</option>

                                    @if(isset($warehousePlacement))
                                        @foreach($warehousePlacement as $placement)

                                            @foreach($placement['rack'] as $rack)
                                                <option value="{{ $rack['rackId'] }}">Gudang
                                                    : {{ $placement['warehouseName'] }} / Area
                                                    : {{ $placement['areaName'] }}
                                                    : Rak - {{ $rack['rackName'] }}</option>
                                            @endforeach
                                        @endforeach
                                    @endif

                                </select>
                                @error('placement') <span text-xs text-red-600>{{ $message }}</span> @enderror
                            </div>
                        </div>


                        <div class="margin-top-20">
                            <label for="description" class="form-label">Rute</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exampleRadios"
                                       id="exampleRadios1"
                                       value="BUY" checked wire:model.live="route">
                                <label class="form-check-label" for="exampleRadios1">
                                    Dapat dibeli
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exampleRadios"
                                       id="exampleRadios2"
                                       value="PRODUCE" wire:model.live="route">
                                <label class="form-check-label" for="exampleRadios2">
                                    Dapat diproduksi
                                </label>
                            </div>


                        </div>

                        <div class="margin-top-8">
                            @if($route == 'PRODUCE')
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="produceOutlet"
                                           id="produceOutlet"
                                           value="PRODUCECENTRALKITCHEN" checked wire:model.live="routeProduce">
                                    <label class="form-check-label" for="produceOutlet">
                                        Diproduksi central kitchen
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="produceOutlet"
                                           id="produceOutlet"
                                           value="PRODUCEOUTLET" checked wire:model.live="routeProduce">
                                    <label class="form-check-label" for="produceOutlet">
                                        Diproduksi outlet
                                    </label>
                                </div>

                            @endif
                        </div>
                    </div>

                    <div class="col-sm-4 offset-1">
                        <div class="d-flex flex-column align-self-center align-items-center">

                            @if ($thumbnail && !$errors->has('$thumbnail'))
                                <img src="{{ $thumbnail->temporaryUrl() }}" width="221" height="221"
                                     @click="document.getElementById('fileInput').click()"
                                >
                            @else
                                <img
                                    src="{{ asset('img/no-image.png') }}"
                                    alt="" srcset="" width="140" @click="document.getElementById('fileInput').click()">
                            @endif
                            <div wire:loading wire:target.prevent="thumbnail">Uploading...</div>
                            @error('thumbnail') <span text-xs text-red-600>{{ $message }}</span> @enderror


                            <input type="file" id="fileInput" wire:model="thumbnail"
                                   style="display: none;"
                                   accept="image/*">


                        </div>
                    </div>
                @endif

            </div>
        </div>
    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
