<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Daftar item
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <div class="dropdown margin-left-10">
                        <button class="btn btn-dropdown-no-icon dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            {{ __('app_locale.button.outlet') }}
                        </button>
                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="#">Action</a></li>
                        </ul>
                    </div>


                    <a href="/warehouse/list-warehouse/add-warehouse" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.tambahGudang') }}</button>
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
