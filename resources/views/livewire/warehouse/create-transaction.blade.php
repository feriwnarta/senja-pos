<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat permintaan stok
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="cancel">Batal
                    </button>

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="create">Lanjutkan
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

            @if($error != '')
                <h1 class="subtitle-3-medium">{{ $error }}</h1>
            @else

                @if(!$isCreate)
                    <div class="col-sm-4 offset-1">
                        {{-- Tanggal --}}
                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">Tanggal</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="date" class="form-control input-default"
                                   id="warehouseInput" placeholder="RBSJ0001" disabled
                                   wire:model="date">
                        </div>


                        {{-- DESKRIPSI --}}
                        <div class="margin-top-24">
                            <label for="description" class="form-label">Catatan</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="description" rows="5"
                                      placeholder="Permintaan stok"
                                      wire:model.blur="note"></textarea>
                        </div>

                    </div>

                @else

                    <div class="col-sm-4 offset-1">

                        <p class="subtitle-3-regular">Permintaan stok #{{ $code }}</p>
                        <p class="subtitle-3-regular margin-top-16">Tanggal: {{ $date }}</p>
                        <p class="subtitle-3-regular margin-top-16">Catatan : {{ $note }}</p>

                    </div>


                    <div class="col-sm-9 offset-1 margin-top-16">
                        <div id="divider"></div>

                        {{-- TODO: FILTER ITEM BELAKANGAN --}}

                        {{-- TABLE ITEM --}}
                        <table class="table-component table table-hover margin-top-16">
                            <thead>
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox" value="" id="selectAllCheckbox">
                                </th>
                                <th>Item</th>
                                <th>SKU</th>
                                <th>Kategori</th>
                                <th>Stok Aktual</th>
                                <th>Stok Tambahan</th>
                            </tr>
                            </thead>

                            <tbody>

                            @if(!empty($items))
                                @foreach($items as $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" id="itemCheckbox1">
                                        </td>
                                        {{--  TODO : Perlu lebih spesifik ke item --}}
                                        <td> {{ $item->name }}</td>
                                        <td>{{ ($item->sku) ?? '-' }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->stockItem->last()->qty_on_hand}}</td>
                                        <td>
                                            <input type="number" class="form-control input-default"
                                                   x-mask="999999999" placeholder="0">
                                        </td>
                                    </tr>

                                @endforeach
                            @endif

                            </tbody>
                        </table>


                    </div>

                @endif

            @endif
        </div>
    </div>


</x-page-layout>
