@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Pesanan Pembelian
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    @if($status == 'Permintaan baru')

                        <button type="btn"
                                class="btn btn-text-only-danger btn-nav margin-left-10"
                        >
                            Tolak
                        </button>

                        <button type="btn"
                                wire:click="processRequest('{{ $purchaseRequests->id }}')"
                                wire:loading.attr="disabled"
                                class="btn btn-text-only-primary btn-nav margin-left-10">Proses
                        </button>
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
            <div class="col-sm-6 offset-1">
                <div id="purchaseRequest">
                    <div>
                        <p class="subtitle-3-regular">Kode referensi</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ $purchaseRequests->reference->requestable->code }}</p>
                    </div>

                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Tanggal</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">
                        <td>{{ Carbon::createFromFormat('Y-m-d H:i:s',  $purchaseRequests->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                        </p>
                    </div>


                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Item pembelian</p>
                        <div id="divider" class="margin-top-6"></div>
                        <table id="" class="table borderless table-hover margin-top-6">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Unit</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(isset($purchaseRequests))
                                @foreach($purchaseRequests->detail as $detail)
                                    <tr wire:key="{{ $loop->iteration }}">
                                        <td>{{ $detail->item->name }}</td>
                                        <td>{{ $detail->qty_buy }}</td>
                                        <td>{{ $detail->item->unit->name }}</td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
