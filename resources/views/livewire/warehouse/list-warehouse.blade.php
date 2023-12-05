<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ __('sidebar_locale.gudang.daftarGudang') }}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default" id="resupplyOutlet"
                                wire:model="selected">
                            <option value="" disabled selected>Semua outlet & central kitchen</option>
                            @foreach($outletCentralKitchenDropdown as $result)
                                <option value="{{ $result->id }}">{{ $result->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <button type="btn"
                            wire:click="addWarehouse"
                            class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.tambahGudang') }}</button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">


        <livewire:warehouse-table wire:model="search"/>

    </div>


</x-page-layout>
