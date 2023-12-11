<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat permintaan stok
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="cancel">Batal
                    </button>

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="create">Lanjutkan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">
        <x-notify::notify/>


    </div>


</x-page-layout>
