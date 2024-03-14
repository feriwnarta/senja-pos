@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail Permintaan Stok
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    {{-- TOMBOL EDIT MUNCUL SELAMA PRODUKSI / PEMBELIAN BELUM DIPROSES --}}
                    @if($requestStock->requestStockHistory->last()->status == 'Baru')
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                        >Edit
                        </button>
                    @endif


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">


        <div class="row">
            <div class="col-sm-4 offset-1">
                <div class="container-input-default">
                    <label for="warehouseInput"
                           class="form-label input-label">Kode</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput" value="{{ $requestStock->code }}"
                           disabled
                    >


                </div>
                <div class="container-input-default margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Tanggal</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <input type="name" class="form-control input-default"
                           id="warehouseInput"
                           value="{{ Carbon::createFromFormat('Y-m-d H:i:s', $requestStock->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}"
                           disabled
                    >
                </div>
                <div class="container-input-default margin-top-24">
                    <label for="warehouseInput"
                           class="form-label input-label">Catatan</label>

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <textarea class="form-control textarea" id="description" rows="5"
                              placeholder="{{ $requestStock->note }}"
                              disabled
                    ></textarea>
                </div>


                <table class="table-component table table-hover margin-top-24" id="tableItemRequest"
                >
                    <thead class="sticky-top">
                    <tr>
                        <th>Item</th>
                        <th>Diminta</th>
                        <th>Diterima</th>
                        <th>Unit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requestStock->requestStockDetail as $requestStock)
                        <tr>
                            <td>{{ $requestStock->item->name }}</td>
                            <td>{{ $requestStock->qty }}</td>
                            <td>{{ $requestStock->qty_accept }}</td>
                            <td>{{ $requestStock->item->unit->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</x-page-layout>

