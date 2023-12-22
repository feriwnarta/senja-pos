<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat central kitchen baru
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


        <div id="content-loaded">
            <div class="row">
                <div class="col-sm-4 offset-1">
                    {{-- KODE OUTLET --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode central kitchen</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="CKCHS01"
                               wire:model.live.debounce.600ms="code">
                        @error('code') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- NAMA OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama central kitchen</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Central kitchen poris"
                               wire:model.live.debounce.600ms="name">
                        @error('name') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- ALAMAT OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Alamat central kitchen</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Jl poris indah"
                               wire:model.live.debounce.600ms="address">
                        @error('address') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- NOMOR TELPON OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nomor telpon central kitchen</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="number" class="form-control input-default"
                               id="warehouseInput" placeholder="08xxxxxxxxx"
                               wire:model.live.debounce.600ms="phone">
                        @error('phone') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- EMAIL OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Email central kitchen</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="email" class="form-control input-default"
                               id="warehouseInput" placeholder="cahayasenja.bandung@gmail.com"
                               wire:model.live.debounce.600ms="email">
                        @error('email') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>


                </div>
            </div>
        </div>
    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
