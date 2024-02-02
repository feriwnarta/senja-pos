@php use App\Utils\IndonesiaCurrency;use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
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

                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <a href="" wire:navigate>
                        <button type="btn"
                                wire:loading.attr="disabled"
                                class="btn btn-text-only-primary btn-nav margin-left-10">Buat Pembelian
                        </button>
                    </a>
                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">
        <x-notify::notify/>
        <div class="row">
            <div class="row">
                <div class="col-sm-12">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio1"
                               autocomplete="off" value="request" wire:change="toggleChange"
                               wire:loading.attr="disabled"
                               checked
                        >
                        <label class="btn btn-outline-primary" for="btnradio1">Permintaan</label>

                        <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio2"
                               autocomplete="off" value="purchase" wire:change="toggleChange"
                               wire:loading.attr="disabled"
                        >
                        <label class="btn btn-outline-primary" for="btnradio2">Pembelian</label>

                        <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio3"
                               autocomplete="off" value="reception" wire:change="toggleChange"
                               wire:loading.attr="disabled">
                        <label class="btn btn-outline-primary" for="btnradio3">Penerimaan</label>

                        <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio4"
                               autocomplete="off" value="payment" wire:change="toggleChange"
                               wire:loading.attr="disabled">
                        <label class="btn btn-outline-primary" for="btnradio4">Pembayaran</label>

                        <input type="radio" class="btn-check" wire:model="toggle" name="btnradio" id="btnradio5"
                               autocomplete="off" value="history" wire:change="toggleChange"
                               wire:loading.attr="disabled">
                        <label class="btn btn-outline-primary" for="btnradio5">Riwayat</label>
                    </div>


                    @if($toggle == 'request')

                        <table class="table borderless table-hover margin-top-28">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Kode permintaan</th>
                                <th scope="col">Referensi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(isset($purchases))

                                @foreach($purchases as $purchase)

                                    <tr wire:key="{{ $loop->iteration }}"
                                        wire:click="detailPurchaseRequest('{{ $purchase->id }}')">
                                        <td class="{{ $purchase->code == null ? '' : 'code'}}">{{ $purchase->code == null ? '* Menunggu diproses' : $purchase->code}}</td>
                                        <td>{{ $purchase->reference->requestable->code }}</td>
                                        <td>{{ Carbon::createFromFormat('Y-m-d H:i:s',  $purchase->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                        <td>{{  $purchase->reference->requestable->note == null ? 'Tanpa catatan' :  $purchase->reference->requestable->note }}</td>
                                        <td>{{  $purchase->history->last()->status }}</td>
                                    </tr>

                                @endforeach
                            @endif

                            </tbody>
                        </table>

                    @elseif($toggle == 'purchase')
                        <table class="table borderless table-hover margin-top-28">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Kode pembelian</th>
                                <th scope="col">Referensi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchases as $purchase)
                                <tr wire:key="{{ $loop->iteration }}"
                                    wire:click="detailPurchased('{{ $purchase->id }}')">
                                    <td class="code">{{ $purchase->code }}</td>
                                    <td>{{ ( $purchase->reference->purchasable->code == null) ? '-' :  $purchase->reference->purchasable->code  }}</td>

                                    <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $purchase->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td>{{ IndonesiaCurrency::formatToRupiah($purchase->detail_sum_total_price) }}</td>
                                    <td>{{ $purchase->history->first()->status }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if(isset($purchases))
                        {{ $purchases->links() }}
                    @endif

                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
