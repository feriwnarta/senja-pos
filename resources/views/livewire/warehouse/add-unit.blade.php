<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Tambah unit
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
                <div class="col-sm-4 offset-1">
                    {{-- KODE UNIT --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="KU004"
                               wire:model.live.debounce.600ms="code">
                        @error('code') <span class="error">{{ $message }}</span> @enderror

                    </div>

                    {{-- NAMA UNIT --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Btl (Botol)"
                               wire:model.live.debounce.600ms="name">
                        @error('name') <span class="error">{{ $message }}</span> @enderror

                    </div>
                </div>
            </div>
        </div>
    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
