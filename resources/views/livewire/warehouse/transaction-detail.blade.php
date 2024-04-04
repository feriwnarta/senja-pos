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
                        @if($this->error == '' && in_array( $warehouseOutbound->history->last()->status , ["Baru", "Permintaan diterima"]))
                            @if($warehouseOutbound->code != '' )

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

        @if($this->error != '')
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
                                @if($mode == '')
                                    <th>Stok</th>
                                @endif
                                <th>Permintaan</th>
                                <th>Dikirim</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($items as $key => $item)

                                @php
                                    $this->outboundItems[] = [
                                          'item_id' => $item->items_id,
                                          'outboundId' => $warehouseOutbound->id,
                                          'qty_on_hand' => $item->item->warehouseItem->last()->stockItem->last()->qty_on_hand,
                                          'qty_send' => number_format($item->qty, 0, '.', '.')
                                    ]

                                @endphp

                                <tr wire:key="{{ $loop->iteration }}">
                                    <td>{{ $item->item->name }}</td>
                                    @if($mode == '')
                                        <td class="{{ $item->item->warehouseItem->last()->stockItem->last()->qty_on_hand == 0 ? 'text-danger' : '' }}">
                                            {{ number_format($item->item->warehouseItem->last()->stockItem->last()->qty_on_hand, 0, '.', '.') }}
                                        </td>
                                    @endif
                                    <td>{{ number_format($item->qty, 0, '.', '.') }}</td>
                                    <td class="d-flex flex-row align-items-center">

                                        <input type="text" class="form-control input-default"
                                               wire:model="outboundItems.{{$key}}.qty_send"
                                            {{ $item->item->warehouseItem->last()->stockItem->last() < $item->qty || $warehouseOutbound->code == null || $this->mode == 'view' || $warehouseOutbound->history->last()->status == 'Bahan dikirim' ? 'disabled' : '' }}
                                        >

                                        @error("outboundItems.{$key}.qty_send")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        @if( $item->item->warehouseItem->last()->stockItem->last()->qty_on_hand < $item->qty && $mode == '' )
                                            <i class="danger-exclamation-icon" data-bs-toggle="tooltip"
                                               data-bs-title="Stok tidak mencukupi" data-bs-placement="right"></i>
                                        @endif
                                    </td>
                                    <td>{{ $item->item->unit->name }}</td>
                                </tr>

                            @endforeach

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
