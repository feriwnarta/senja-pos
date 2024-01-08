@php use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
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
                        <select
                            class="form-select input-default {{ !$isWarehouseSelected ? 'border border-danger text-danger' : '' }}"
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
                           autocomplete="off" value="request" wire:change="toggleChange"
                           wire:loading.attr="disabled"
                    >
                    <label class="btn btn-outline-primary" for="btnradio1">Permintaan stok</label>

                    <input type="radio" class="btn-check" wire:model="toggle" name="btnradio2" id="btnradio2"
                           autocomplete="off" value="stockIn" wire:change="toggleChange"
                           wire:loading.attr="disabled"
                           checked>
                    <label class="btn btn-outline-primary" for="btnradio2">Stok masuk</label>

                    <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio3"
                           autocomplete="off" value="stockOut" wire:change="toggleChange"
                           wire:loading.attr="disabled">
                    <label class="btn btn-outline-primary" for="btnradio3">Stok keluar</label>
                </div>


                @if($urlQuery == 'request')
                    <table id="" class="table borderless table-hover margin-top-28">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Kode Permintaan stok</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Catatan</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($requestStock as $request)
                            <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            >
                                <td class="code">{{ $request->code }}</td>
                                <td> {{ Carbon::createFromFormat('Y-m-d H:i:s', $request->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                <td>{{ ($request->note == null) ? 'tanpa catatan' : $request->note }}</td>
                                <td>{{ optional($request->requestStockHistory->last())->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $requestStock->links() }}

                @elseif($urlQuery == 'stockIn')

                    <table id="" class="table borderless table-hover margin-top-28">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Kode stok masuk</th>
                            <th scope="col">Kode referensi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Catatan</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($requestStock as $request)
                            <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                                wire:click="detailReceipt('{{ $request->id }}')"
                            >
                                <td class="code">Menunggu diterima</td>
                                <td class="code">{{ $request->code }}</td>
                                <td> {{ Carbon::createFromFormat('Y-m-d H:i:s', $request->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                <td>{{ ($request->note == null) ? 'tanpa catatan' : $request->note }}</td>
                                <td>{{ optional($request->requestStockHistory->last())->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @elseif($urlQuery == 'stockOut')

                    <table id="" class="table borderless table-hover margin-top-28">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Kode Stok Keluar</th>
                            <th scope="col">Kode referensi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Catatan</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($requestStock as $request)

                            <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                                wire:click="detail('{{ $request->id }}')">

                                <td class="{{ $request->code == null ? 'text-danger' : 'code' }}">{{ ($request->code == null) ? 'Menunggu diterima' : $request->code }}</td>
                                <td class="code">{{ $request->production->code }}</td>
                                <td> {{ Carbon::createFromFormat('Y-m-d H:i:s', $request->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                <td>{{ ($request->note == null) ? 'tanpa catatan' : $request->note }}</td>
                                <td>{{ $request->history()->latest()->first()->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </table>
                @endif
            </div>

        </div>


    </div>


</x-page-layout>
