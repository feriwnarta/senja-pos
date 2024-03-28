@php use App\Utils\IndonesiaCurrency;use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Pembelian
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    @if($purchaseStatus == 'Dibuat')

                        <a href="" wire:navigate>
                            <button type="btn"
                                    wire:loading.attr="disabled"
                                    wire:click="purchasedSend"
                                    class="btn btn-text-only-primary btn-nav margin-left-10">Kirim
                            </button>
                        </a>

                    @endif
                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">
        <x-notify::notify/>
        <div class="row">
            <div class="col-sm-5">
                {{-- Tanggal --}}
                <div class="container-input-default">
                    <label for="warehouseInput"
                           class="form-label input-label">Tanggal</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="text" class="form-control input-default"
                           id="warehouseInput" disabled
                           value="{{ Carbon::createFromFormat('Y-m-d H:i:s',  $purchase->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}"
                    >
                </div>

                {{-- KODE PEMBELIAN --}}
                <div class="container-input-default  margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Kode pembelian</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="text" class="form-control input-default"
                           id="warehouseInput" disabled
                           value="{{ $purchase->code }}"
                    >
                </div>

                {{-- KODE REFERENSI --}}
                <div class="container-input-default margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Kode referensi</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="text" class="form-control input-default"
                           id="warehouseInput" disabled
                           value="{{ $purchase->reference->purchasable->code }}"
                    >
                </div>

                {{-- SUPPLIER --}}
                <div class="container-input-default margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Pemasok</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="text" class="form-control input-default"
                           id="warehouseInput" disabled
                           value="{{ $purchase->supplier->name }}"
                    >
                </div>

                <div class="row align-items-end margin-top-24">

                    {{-- TIPE PEMBAYARAN --}}
                    <div class="container-input-default col-md">
                        <label for="warehouseInput"
                               class="form-label input-label">Tipe pembayaran</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="text" class="form-control input-default"
                               id="warehouseInput" disabled
                               value="{{ $purchase->payment_scheme }}"
                        >
                    </div>


                    {{-- TIPE PEMBAYARAN --}}
                    <div class="container-input-default col-md">
                        <label for="warehouseInput"
                               class="form-label input-label">Tenggat waktu</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="text" class="form-control input-default"
                               id="warehouseInput" disabled
                               value="{{Carbon::createFromFormat('Y-m-d H:i:s', $purchase->due_date )->locale('id_ID')->isoFormat('D MMMM Y') }}"
                        >
                    </div>


                    <div class="margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Item pembelian</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                    </div>
                </div>

            </div>

            <div class="col-sm-12">
                <table id="" class="table borderless table-hover">
                    <thead class="table-head-color">
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Jumlah diterima</th>
                        <th scope="col">Jumlah pembelian</th>
                        <th scope="col">Harga per unit</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchase->detail as $purchaseDetail)
                        <tr wire:key="{{ $loop->iteration }}">
                            <td>{{ $purchaseDetail->item->name }}</td>
                            <td>{{ $purchaseDetail->item->unit->name }}</td>
                            <td>{{ number_format($purchaseDetail->qty_accept, 0, '.', ',') }}</td>
                            <td>{{ number_format($purchaseDetail->qty_buy, 0, '.', ',') }}</td>
                            <td>{{ IndonesiaCurrency::formatToRupiah($purchaseDetail->single_price) }}</td>
                            <td>{{ IndonesiaCurrency::formatToRupiah($purchaseDetail->total_price) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</x-page-layout>
