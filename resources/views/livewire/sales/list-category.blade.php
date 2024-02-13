<x-page-layout>
    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Kategori menu
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                            placeholder="{{ __('app_locale.input.cari') }}" aria-label="Search"
                            wire:model.live.debounce.600ms="search">
                    </form>

                    {{-- <div class="dropdown margin-left-10">
                        <select class="form-select input-default {{ ($notSelected) ? 'border border-danger' : '' }}"
                                id="resupplyOutlet" wire:model="selected">
                            <option value="" disabled selected>Pilih Outlet</option>
                            @foreach ($outletCentralKitchenDropdown as $result)
                                <option value="{{ $result->id }}">{{ $result->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <a href="/sales/list-category/create-category" wire:navigate>
                        <button type="btn" wire:loading.attr="disabled"
                            class="btn btn-text-only-primary btn-nav margin-left-10">+ Buat kategori menu
                        </button>
                    </a>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

    </x-slot>

    <div id="content-loaded">

        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table borderless table-hover">
                    <thead class="table-head-color">
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama Kategori</th>
                        </tr>
                    </thead>
                    <tbody id="listCentralKitchen">
                        
                        @foreach ($categoryMenus as $data)
                            <tr class="items-table-head-color" id="po1" wire:click="">
                                <td class="code">
                                    {{ $data->code }}</td>
                                <td>{{ $data->name }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $categoryMenus->links() }}

            </div>
        </div>
    </div>

</x-page-layout>
