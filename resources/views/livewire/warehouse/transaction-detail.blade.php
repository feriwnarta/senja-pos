@php use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail stok keluar
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-danger btn-nav margin-left-10">
                            Batal
                        </button>
                    </div>

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:loading.attr="disabled" wire:click="acceptAndNext">Terima dan lanjutkan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">
        <x-notify::notify/>

        @if($error != '')
            <p class="subtitle-3-medium">{{ $error }}</p>
        @else

            <div class="row">
                <div class="col-sm-5 offset-1">
                    <div>
                        <p class="subtitle-3-regular">Kode item keluar</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium {{  $warehouseOutbound->code == null ? 'text-danger' : ''}}">
                            * Menunggu diterima</p>
                    </div>

                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Kode referensi</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ $warehouseOutbound->production->code }}</p>
                    </div>


                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Diminta oleh</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ $warehouseOutbound->production->centralKitchen->name }}</p>
                    </div>

                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Tanggal</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ Carbon::createFromFormat('Y-m-d H:i:s', $warehouseOutbound->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</p>
                    </div>


                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Informasi item</p>
                        <div id="divider" class="margin-top-6"></div>

                        <table class="table-component table table-hover margin-top-16"
                               id="tableItemRequest"
                        >
                            <thead class="sticky-top">
                            <tr>
                                <th>Item</th>
                                <th>Jumlah</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($warehouseOutbound->outboundItem->isNotEmpty())

                                @foreach($warehouseOutbound->outboundItem as $item)
                                    <tr wire:key="{{ $loop->iteration }}">

                                        <td>{{ $item->item->name }}</td>
                                        <td>{{ $item->qty}}</td>
                                        <td>{{ $item->item->unit->name}}</td>

                                    </tr>

                                @endforeach

                            @else
                                <h1>Gagal mendapatkan item yang diminta</h1>
                            @endif


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif


    </div>

</x-page-layout>
