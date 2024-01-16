@php use Carbon\Carbon; @endphp
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
                    <table id="" class="table borderless table-hover">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Kode</th>
                            <th scope="col">Kode referensi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($purchases as $purchase)
                            <tr id="{{ $loop->iteration }}" wire:click="detailPurchase('{{ $purchase->id }}')">
                                <td>{{   $purchase->code == null ? 'Menunggu divalidasi' : $purchase->code  }}</td>
                                <td>{{ $purchase->reference->purchasable->code }}</td>
                                <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $purchase->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                                <td>{{ $purchase->history->last()->status }}</td>
                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                    {{ $purchases->links() }}

                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
