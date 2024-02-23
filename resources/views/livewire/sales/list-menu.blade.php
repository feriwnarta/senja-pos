@php use illuminate\Support\Str; @endphp
<x-page-layout>
    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Daftar menu
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

                    <a href="/sales/list-menu/create-menu" wire:navigate>
                        <button type="btn" wire:loading.attr="disabled"
                            class="btn btn-text-only-primary btn-nav margin-left-10">+ Buat Menu
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
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>

                        </tr>
                    </thead>
                    <tbody id="listCentralKitchen">

                        @foreach ($restaurantMenus as $data)
                            <tr class="items-table-head-color" id="po1" wire:click="">
                                <td class="code align-middle">{{ $data->code }}</td>
                                <td class="d-flex flex-row align-items-center gap-3">
                                    {{-- DATA EXIST BUT CAN'T BE SHOWN --}}
                                    @if ($data->thumbnail && !$errors->has('thumbnail'))
                                        <img src="{{ asset(Storage::url("$data->thumbnail")) }}" alt="{{ $data->name }}" width="50px" height="50px">
                                    @endif
                                    {{ $data->name }}
                                </td>
                                <td class="align-middle">{{ $data->category->name }}</td>
                                <td class="align-middle">Rp. {{ number_format($data->price) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $restaurantMenus->links() }}

            </div>
        </div>

    </div>

</x-page-layout>
