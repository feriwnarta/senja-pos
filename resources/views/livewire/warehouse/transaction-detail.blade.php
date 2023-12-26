@php use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail stok keluar
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-danger btn-nav margin-left-10">
                            Batal
                        </button>
                    </div>
                    
                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="create"
                            wire:loading.attr="disabled">Terima dan lanjutkan
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
