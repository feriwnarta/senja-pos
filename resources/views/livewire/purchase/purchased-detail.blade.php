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


    <div id="content-loaded" wire:init="loadPosts">
        <x-notify::notify/>
        <div class="row">
            <div class="row">
                <div class="col-sm-12">

                </div>
            </div>
        </div>
    </div>

    </div>


</x-page-layout>
