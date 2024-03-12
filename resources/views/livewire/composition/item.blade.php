@php use App\Models\CentralKitchen;use App\Models\Outlet;use Illuminate\Support\Facades\Storage; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Item
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default {{ ($notSelected) ? 'border border-danger' : '' }}"
                                id="resupplyOutlet" wire:model.live="selected">
                            <option value="" disabled selected>Semua outlet & central kitchen</option>
                            @foreach($outletCentralKitchenDropdown as $result)
                                <option
                                    value="{{ $result->id }}">{{ ($result instanceof CentralKitchen)  ? "CENTRAL - {$result->name}" : "OUTLET - {$result->name}" }}</option>
                            @endforeach
                        </select>
                    </div>


                    <button type="btn"
                            wire:click="addItem"
                            class="btn btn-text-only-primary btn-nav margin-left-10">+ Buat
                        item
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>

        </div>
    </x-slot>
    <div id="content-loaded">


        <table id="" class="table borderless table-hover margin-top-28">
            <thead class="table-head-color">
            <tr>
                <th scope="col">Kode</th>
                <th scope="col">Name</th>
                <th scope="col">Unit</th>
                <th scope="col">Kategori</th>
            </tr>
            </thead>
            <tbody>

            @foreach($items as $item)
                <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                    wire:click="view('{{ $item->id }}')">
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->unit->name }}</td>
                    <td>{{ $item->category->name }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>


</x-page-layout>

