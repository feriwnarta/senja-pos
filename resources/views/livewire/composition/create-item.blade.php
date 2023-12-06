<x-page-layout>


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
                                @click="$dispatch('cancel-edit-warehouse')">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            @click="$dispatch('saveUnit')"
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
                            @error('code') <span class="error">{{ $message }}</span> @enderror

                        </div>

                        {{-- NAMA ITEM --}}
                        <div class="container-input-default margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Nama item</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="Daging ayam"
                                   wire:model.live.debounce.600ms="name">
                            @error('name') <span class="error">{{ $message }}</span> @enderror

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
                                <select class="form-select input-default" wire:model="unit">
                                    <option value="" disabled selected>Pilih unit</option>

                                </select>
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
                                        @error('inStock') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="description" class="form-label">Unit</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <select class="form-select input-default"
                                                disabled>
                                            <option value="" disabled selected>Pilih unit</option>
                                            <!-- Opsi lainnya bisa ditambahkan di sini -->
                                        </select>
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
                                        @error('minimumStock') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-input-default">
                                        <label for="description" class="form-label">Unit</label>
                                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                                        <select class="form-select input-default"
                                                disabled>
                                            <option value="" disabled selected>Pilih unit</option>
                                            <!-- Opsi lainnya bisa ditambahkan di sini -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="margin-top-20">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Kategori item</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <select class="form-select input-default" id="resupplyOutlet"
                                        wire:model="category">
                                    <option value="" disabled selected>Pilih kategori item</option>

                                </select>
                            </div>
                        </div>


                        <div class="margin-top-20">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Penempatan</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <select class="form-select input-default" id="resupplyOutlet"
                                        wire:model="placement">
                                    <option value="" disabled selected>Pilih area dan rak</option>

                                </select>
                            </div>
                        </div>


                        <div class="margin-top-20">
                            <label for="description" class="form-label">Rute</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                       value="buy" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                    Dapat dibeli
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                       value="produce">
                                <label class="form-check-label" for="exampleRadios2">
                                    Dapat diproduksi
                                </label>
                            </div>

                        </div>

                    </div>

                @endif

            </div>
        </div>
    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
