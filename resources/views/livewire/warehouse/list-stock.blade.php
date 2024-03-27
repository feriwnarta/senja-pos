<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Stok
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <div class="dropdown margin-left-10">
                        <select
                            class="form-select input-default"
                            id="resupplyOutlet" wire:model="selectedWarehouse" wire:change="handleSelectWarehouse"
                            wire:loading.attr="disabled">
                            @if(isset($warehouses) && count($warehouses) > 0)
                                @foreach($warehouses as $warehouse)
                                    <option wire:key="{{ $loop->iteration }}"
                                            value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @else
                                <option>Tidak ada gudang</option>
                            @endif
                        </select>
                    </div>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>


        <div id="content-loaded">
            <table id="" class="table borderless table-hover margin-top-28">
                <thead class="table-head-color">
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Qty</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($items))
                    @foreach($items as $item)
                        <tr wire:key="{{ $loop->iteration }}">
                            <td>{{ $item->warehouseItem->items->name }}</td>
                            <td>{{ $item->warehouseItem->items->unit->name }}</td>
                            <td>{{ $item->qty_on_hand }}</td>
                            {{--                            <td>{{ $item->stockItem }}</td>--}}
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

    </x-slot>


</x-page-layout>

