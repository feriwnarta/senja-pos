@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Proses Pembelian
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button type="btn"
                            class="btn btn-text-only-danger btn-nav margin-left-10"
                    >
                        Tolak
                    </button>

                    <button type="btn"
                            wire:click="validate({{ $purchase->id }})"
                            wire:loading.attr="disabled"
                            class="btn btn-text-only-primary btn-nav margin-left-10">Validasi
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
            <div class="col-sm-6 offset-1">

                <div>
                    <p class="subtitle-3-regular">Kode pembelian</p>
                    <div id="divider" class="margin-top-6"></div>
                    <p class="margin-top-6 subtitle-3-medium">* Menunggu dibuat</p>
                </div>

                <div class="margin-top-24">
                    <p class="subtitle-3-regular">Kode referensi</p>
                    <div id="divider" class="margin-top-6"></div>
                    <p class="margin-top-6 subtitle-3-medium">{{ $purchase->reference->purchasable->code }}</p>
                </div>

                <div class="margin-top-24">
                    <p class="subtitle-3-regular">Tanggal</p>
                    <div id="divider"
                         class="margin-top-6"></div>
                    <p class="margin-top-6 subtitle-3-medium">{{ Carbon::createFromFormat('Y-m-d H:i:s', $purchase->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</p>
                </div>

                {{ $purchase->detail }}

                <div class="margin-top-24">
                    <p class="subtitle-3-regular">Item permintaan</p>
                    <div id="divider" class="margin-top-6"></div>
                    <table id="" class="table borderless table-hover margin-top-6">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Jumlah permintaan</th>
                            <th scope="col">Unit</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($purchase->detail as $item)
                            <tr wire:key="{{ $loop->iteration }}">
                                <td>{{ $item->item->name }}</td>
                                <td>{{ $item->qty_buy }}</td>
                                <td>{{ $item->item->unit->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
