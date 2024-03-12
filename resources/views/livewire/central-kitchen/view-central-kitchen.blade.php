<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail central kitchen
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="save"
                    >
                        <span wire:loading wire:target="save" class="spinner-border text-warning" role="status">
                        </span>
                        Update
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
                               class="form-label input-label">Kode</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" value="{{ $centralKitchen->code }}" disabled
                        >

                    </div>

                    {{-- NAMA OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" value="{{ $centralKitchen->name }}" disabled
                        >

                    </div>

                    {{-- ALAMAT OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Alamat</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput"
                               value="{{ $centralKitchen->address }}" disabled
                        >

                    </div>

                    {{-- NOMOR TELPON OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nomor telpon</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="number" class="form-control input-default"
                               id="warehouseInput" value="{{ $centralKitchen->phone }}" disabled
                        >

                    </div>

                    {{-- EMAIL OUTLET --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Email</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="email" class="form-control input-default"
                               id="warehouseInput" value="{{ $centralKitchen->email }}" disabled
                        >

                    </div>


                </div>
            </div>
        </div>
    </x-slot>


</x-page-layout>
