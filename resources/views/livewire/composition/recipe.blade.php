<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Resep
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default"
                                id="resupplyOutlet" wire:model="typeRecipe">
                            <option value="recipeMenu" selected>Resep menu</option>
                            <option value="recipeSemi">Resep 1 / 2 jadi</option>
                        </select>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="createRecipe">Buat
                        Resep
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
