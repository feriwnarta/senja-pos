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

                @if($this->mode == '')
                    <div id="nav-action-button" class="d-flex flex-row align-items-center">

                        <div class="dropdown margin-left-10">
                            <button type="btn"
                                    class="btn btn-text-only-danger btn-nav margin-left-10">
                                Batal
                            </button>
                        </div>

                        @if($warehouseOutbound->code != '')
                            <button type="btn"
                                    class="btn btn-text-only-primary btn-nav margin-left-10"
                                    wire:loading.attr="disabled" wire:click="sendItem">Kirim
                            </button>
                        @else
                            <button type="btn"
                                    class="btn btn-text-only-primary btn-nav margin-left-10"
                                    wire:loading.attr="disabled" wire:click="acceptAndNext">Terima dan lanjutkan
                            </button>
                        @endif

                    </div>
                @endif
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
                            {{ $warehouseOutbound->code == null ? '* Menunggu diterima' : $warehouseOutbound->code }}</p>
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
                                <th>Stok</th>
                                <th>Permintaan</th>
                                <th>Dikirim</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($warehouseOutbound->outboundItem->isNotEmpty())
                                @foreach($warehouseOutbound->outboundItem as $key => $item)
                                    {{-- TODO: perbaiki ini untuk dilakukan di service  --}}
                                    @php
                                        // Temukan item dengan ID yang sama dalam koleksi outboundItems
                                        $existingItem = collect($this->outboundItems)->firstWhere('id', $item->id);

                                        // Jika item sudah ada, tambahkan jumlah kuantitas
                                        if ($existingItem) {
                                            $existingItem['qty_send'] += $item->qty;
                                        } else {
                                            // Jika item belum ada, tambahkan item baru
                                            $this->outboundItems[] = [
                                                'item_id' => $item->items_id,
                                                'outboundId' => $warehouseOutbound->id,
                                                'qty_on_hand' => $item->item->stockItem->last()->qty_on_hand,
                                                'qty_send' => $item->qty,
                                            ];
                                        }
                                    @endphp

                                    <tr wire:key="{{ $loop->iteration }}">
                                        <td>{{ $item->item->name }}</td>
                                        <td class="{{ $item->item->stockItem->last()->qty_on_hand == 0 ? 'text-danger' : '' }}">
                                            {{ $item->item->stockItem->last()->qty_on_hand }}
                                        </td>
                                        <td>{{ $item->qty }}</td>
                                        <td class="d-flex flex-row align-items-center">

                                            <input type="text" class="form-control input-default"
                                                   wire:model="outboundItems.{{$key}}.qty_send"
                                                {{ $item->item->stockItem->last()->qty_on_hand < $item->qty || $warehouseOutbound->code == null || $this->mode == 'view' ? 'disabled' : '' }}
                                            >

                                            @error("outboundItems.{$key}.qty_send")
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                            @if( $item->item->stockItem->last()->qty_on_hand < $item->qty )
                                                <i class="danger-exclamation-icon" data-bs-toggle="tooltip"
                                                   data-bs-title="Stok tidak mencukupi" data-bs-placement="right"></i>
                                            @endif
                                        </td>
                                        <td>{{ $item->item->unit->name }}</td>
                                    </tr>
                                @endforeach

                            @else
                                {{-- Handle case when outbound items are empty --}}
                                <tr>
                                    <td colspan="5">No outbound items available.</td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif


    </div>

</x-page-layout>


@section('footer-script')

    <script>
        $(() => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        })

    </script>
@endsection
