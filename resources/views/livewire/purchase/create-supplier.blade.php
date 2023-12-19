<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat Pemasok
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button type="btn"
                            class="btn btn-text-only-danger btn-nav margin-left-10"
                    >
                        Batal
                    </button>

                    <button type="btn"
                            wire:click="addWarehouse"
                            wire:loading.attr="disabled"
                            class="btn btn-text-only-primary btn-nav margin-left-10">Simpan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">
        <div class="row">
            <div class="col-sm-6 offset-1">

                {{-- NAME --}}
                <div class="container-input-default">
                    <label for="warehouseInput"
                           class="form-label input-label">Nama pemasok</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Masukan nama pemasok"
                           wire:model.live.debounce.600ms="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror

                </div>


                {{-- Nomor telepon --}}
                <div class="container-input-default margin-top-16">
                    <label for="warehouseInput"
                           class="form-label input-label">Nomor telpon</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Masukan nomor telepon"
                           wire:model.live.debounce.600ms="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror

                </div>


                {{-- Email --}}
                <div class="container-input-default margin-top-16">
                    <label for="warehouseInput"
                           class="form-label input-label">Email</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Masukan email pemasok"
                           wire:model.live.debounce.600ms="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror

                </div>

                {{-- Kota --}}
                <div class="container-input-default margin-top-16">
                    <label for="warehouseInput"
                           class="form-label input-label">Kota</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Masukan kota pemasok"
                           wire:model.live.debounce.600ms="name">
                    @error('name') <span class="error">{{ $message }}</span> @enderror

                </div>


                {{-- Negara dan kode pos --}}
                <div class="container-input-default margin-top-16">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="container-input-default">
                                <label for="warehouseInput" class="form-label input-label">Negara</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <input type="number" class="form-control input-default" id="warehouseInput"
                                       placeholder="0" wire:model.live.debounce.600ms="inStock">
                                @error('inStock') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="container-input-default">
                                <label for="description" class="form-label">Kode pos</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <input type="text" class="form-control input-default"
                                       wire:model="unitName" placeholder="Masukan kode pos pemasok">

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="margin-top-16">
                    <label for="addressWarehouse" class="form-label">Alamat</label>
                    <div id="divider" class="margin-symmetric-vertical-6"></div>
                    <textarea class="form-control textarea" id="addressWarehouse" rows="5"
                              placeholder="Jln. Ki Hajar Dewantoro"
                              wire:model.blur="addressWarehouse"></textarea>
                    @error('addressWarehouse') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
