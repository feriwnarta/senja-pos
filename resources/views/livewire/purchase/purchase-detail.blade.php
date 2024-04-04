@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat pembelian
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <button type="btn"
                            class="btn btn-text-only-secondary btn-nav margin-left-10"
                    >
                        Ekspor
                    </button>

                    <button type="btn"
                            wire:click=""
                            wire:loading.attr="disabled"
                            class="btn btn-text-only-primary btn-nav margin-left-10">Buat pembelian
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

        </div>
    </div>

    </div>


</x-page-layout>

