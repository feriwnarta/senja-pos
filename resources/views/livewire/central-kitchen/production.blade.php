<x-page-layout>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Produksi
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default" id="resupplyOutlet"
                                wire:model="selected">
                            <option value="" disabled selected>Semua central kitchen</option>

                            @if(!empty($centralKitchens))

                                @foreach($centralKitchens as $centralKitchen)
                                    <option value="{{ $centralKitchen->id }}">{{ $centralKitchen->name }}</option>
                                @endforeach
                            @endif


                        </select>
                    </div>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">


    </div>


    <script>

    </script>


</x-page-layout>
