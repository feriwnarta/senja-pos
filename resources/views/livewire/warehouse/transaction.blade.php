@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Transaksi
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
                                id="resupplyOutlet" wire:model="selected" wire:change="selectWarehouse"
                                wire:loading.attr="disabled">
                            <option value="all" selected disabled>Semua gudang</option>
                            @if(!empty($warehouses))

                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse['id'] }}">{{ $warehouse['name'] }}</option>
                                @endforeach

                            @endif
                        </select>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="create"
                            wire:loading.attr="disabled">Buat
                        permintaan stok
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

            <div class="col-sm-12">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio1"
                           autocomplete="off"
                           value="request"
                           checked wire:change="toggleChange" wire:loading.attr="disabled">
                    <label class="btn btn-outline-primary" for="btnradio1">Permintaan stok</label>

                    <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio2"
                           autocomplete="off"
                           value="stockIn" wire:change="toggleChange" wire:loading.attr="disabled">
                    <label class="btn btn-outline-primary" for="btnradio2">Stok masuk</label>

                    <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio3"
                           autocomplete="off"
                           value="stockOut" wire:change="toggleChange" wire:loading.attr="disabled">
                    <label class="btn btn-outline-primary" for="btnradio3">Stok keluar</label>
                </div>

                <table id="" class="table borderless table-hover margin-top-28">
                    <thead class="table-head-color">
                    <tr>
                        <th scope="col">Kode Permintaan stok</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Catatan</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody id="listPurchaseOrder">
                    {{ $requestStock }}
                    @foreach($requestStock as $request)
                        <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            wire:click="">
                            <td>{{ $request->code }}</td>
                            <td> {{ Carbon::createFromFormat('Y-m-d H:i:s', $request->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                            <td>{{ ($request->note == null) ? 'tanpa catatan' : $request->note }}</td>
                            <td>{{ optional($request->requestStockHistory->last())->status }}</td>

                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>

        </div>


    </div>


</x-page-layout>
