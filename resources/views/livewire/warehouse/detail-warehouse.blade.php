<x-page-layout>

    <x-slot name="sidebar">
        <div wire:ignore>
            <livewire:components.sidebar.sidebar/>
        </div>
    </x-slot>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ __('app_locale.text.detailGudang') }}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-secondary btn-nav margin-left-10">{{ __('app_locale.button.hapus') }}</button>
                    </div>


                    <a href="/warehouse/list-warehouse/add-warehouse" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.edit') }}</button>
                    </a>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">


    </div>


</x-page-layout>
