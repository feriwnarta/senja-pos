@php use Carbon\Carbon; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Edit Permintaan Stok
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    {{-- TOMBOL EDIT MUNCUL SELAMA PRODUKSI / PEMBELIAN BELUM DIPROSES --}}
                    @if($requestStock->requestStockHistory->last()->status == 'Baru' || $requestStock->requestStockHistory->last()->status == 'Penerimaan dibatalkan' )
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                wire:click="saveEdit"
                        >Simpan
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
                              wire:model="note"
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requestStockDetail as $key =>  $requestStock)
                        <tr wire:key="{{ $loop->iteration }}">
                            <td>

                                @if(!empty($requestStock['items']['name']))
                                    {{ $requestStock['items']['name'] }}
                                @else
                                    <select wire:click="loadData" class="form-select input-default"
                                            wire:change="selectedItem('{{ $key }}')"
                                            wire:model.live="selectedId"
                                    >
                                        <option value="" selected disabled>Pilih Item
                                        </option>
                                        @
                                        @if(isset($items))
                                            @foreach($items as $item)
                                                <option value="{{ $item->items->id }}">{{ $item->items->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                @endif
                                @error("requestStockDetail.$key.items.id") <span
                                    class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>

                                <input type="text" class="form-control input-default"
                                       x-mask:dynamic="$money($input, ',', '.')"
                                       wire:model.live="requestStockDetail.{{ $key }}.qty_request">
                                @error("requestStockDetail.$key.qty_request") <span
                                    class="text-danger">{{ $message }}</span>
                                @enderror

                            </td>
                            <td>{{ $requestStock['qty_accept'] }}</td>
                            <td>{{ $requestStock['items']['unit'] }}
                            <td class="delete">
                                <i class="x-icon" wire:click="delete('{{ $key }}')"></i>
                            </td>
                        </tr>
                    @endforeach
                    <tr id="divideContent">
                        <td colspan="6" class="span-all-columns"
                            style="padding-bottom: 5px; padding-top: 5px;">
                            <button class="btn icon-text container" type="button" id="addItem"
                                    wire:click="addSelectedItem">
                                + Tambah item
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>


    {{--    <!-- Modal -->--}}
    {{--    <div class="modal fade" id="modalSelecNewItem" tabindex="-1" wire:ignore.self>--}}
    {{--        <div class="modal-dialog modal-lg modal-dialog-scrollable">--}}
    {{--            <div class="modal-content" x-intersect="$wire.loadModalData()">--}}
    {{--                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">--}}
    {{--                    <h1 class="modal-title modal-input-title fs-5">Pilih item</h1>--}}
    {{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
    {{--                </div>--}}
    {{--                <div class="modal-body">--}}
    {{--                    <livewire:warehouse.components.modal.add-item-for-edit-request-modal--}}
    {{--                        :requestStockId="$requestStockId"/>--}}
    {{--                </div>--}}
    {{--                <div class="modal-footer">--}}
    {{--                    <div>--}}
    {{--                        <button class="btn text-only-outlined cancel-btn" data-bs-dismiss="modal">--}}
    {{--                            Batal--}}
    {{--                        </button>--}}
    {{--                        <button class="btn btn-text-only-primary margin-left-10"--}}
    {{--                                wire:click="openModalNewItem">Simpan--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</x-page-layout>

