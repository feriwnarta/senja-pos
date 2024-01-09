@php use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Item masuk
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="create"
                            wire:loading.attr="disabled">Validasi dan terima
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

            <div class="col-sm-5 offset-1">

                @if($error != '')
                    <h1 class="subtitle-3-medium"> {{ $error }}</h1>
                @else
                    <div>
                        <p class="subtitle-3-regular">Kode penerimaan</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">-</p>
                    </div>

                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Kode referensi</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ $requestStock->code }}</p>
                    </div>

                @endif
            </div>

        </div>


    </div>


</x-page-layout>
