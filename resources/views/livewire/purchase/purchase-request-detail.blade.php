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
            @if($status == 'Permintaan baru')
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
            @elseif($status == 'Diproses')
                <div id="purchaseRequestOnProcess">
                    <div class="col-sm-5">

                        {{--                {{ $purchaseRequest }}--}}

                        {{-- Tanggal --}}
                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">Tanggal</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="text" class="form-control input-default"
                                   id="warehouseInput" disabled
                                   value="{{ Carbon::createFromFormat('Y-m-d H:i:s',  $currentDateTime)->locale('id_ID')->isoFormat('D MMMM Y') }}"
                            >
                        </div>


                        {{-- Kode referensi --}}
                        <div class="container-input-default margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Referensi</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="text" class="form-control input-default"
                                   id="warehouseInput" disabled
                                   value="{{ $purchaseRequests->reference->requestable->code }}"
                            >
                        </div>


                        <div class="container-input-default margin-top-24">
                            <div class="row align-items-end">

                                <div class="col-md-6">
                                    <label for="warehouseInput"
                                           class="form-label input-label">Pemasok</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <select class="form-select input-default"
                                            id="resupplyOutlet">
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier['id'] }}"
                                                    checked>{{  $supplier['name']  }}</option>

                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label margin-left-8" for="flexCheckDefault">
                                            Multiple Supplier
                                        </label>
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="container-input-default margin-top-24">
                            <div class="row align-items-end">

                                <div class="col-md-6">
                                    <label for="warehouseInput"
                                           class="form-label input-label">Tipe pembayaran</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <select class="form-select input-default"
                                            id="resupplyOutlet">
                                        <option value="" disabled selected>PT Meat Fresh</option>

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label margin-left-8" for="flexCheckDefault">
                                            Multiple Payment
                                        </label>
                                    </div>

                                </div>

                            </div>
                        </div>


                        {{-- DESKRIPSI --}}
                        <div class="margin-top-24">
                            <label for="description" class="form-label">Catatan</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="description" rows="5"
                                      placeholder=""
                                      wire:model.blur="note"></textarea>
                        </div>

                        <div class="margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Tanggal</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                        </div>
                    </div>

                    {{ json_encode($purchaseRequests) }}

                    <div class="col-sm-12">
                        <table id="" class="table borderless table-hover">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">
                                    <input class="form-check-input" type="checkbox" value="" id="selectAllCheckbox"
                                           wire:model="selectAll">
                                </th>
                                <th scope="col">Item</th>
                                <th scope="col">Pemasok</th>
                                <th scope="col">Pembayaran</th>
                                <th scope="col">Stok aktual</th>
                                <th scope="col">Stok Diminta</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Harga per unit</th>
                                <th scope="col">Jumlah pembelian</th>
                                <th scope="col">Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($purchaseRequests->detail as $key => $detail)
                                <tr wire:key="{{ $loop->iteration }}">
                                    <td></td>
                                    <td>{{ $detail->item->name }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $purchaseRequests->reference->requestable->warehouse->warehouseItem->last()->stockItem->last()->qty_on_hand }}</td>
                                    <td>{{ $detail->qty_buy }}</td>
                                    <td>{{ $detail->item->unit->name }}</td>
                                    <td>{{ IndonesiaCurrency::formatToRupiah($purchaseRequests->reference->requestable->warehouse->warehouseItem->last()->stockItem->last()->avg_cost) }}</td>
                                    <td>
                                        <input type="number" class="form-control input-default"
                                               value="{{$detail->qty_buy  }}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif
        </div>
    </div>

    </div>


</x-page-layout>
