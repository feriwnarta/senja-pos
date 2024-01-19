@php use App\Utils\IndonesiaCurrency;use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat Pembelian
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
                    @elseif($status == 'Diproses')
                        <button type="btn"
                                wire:click="createPurchase('{{ $purchaseRequests->id }}')"
                                wire:loading.attr="disabled"
                                class="btn btn-text-only-primary btn-nav margin-left-10">Buat pembelian
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
                                            id="supplier"
                                            wire:model.live.600ms="supplier"
                                        {{ $isMultipleSupplier == true ? 'disabled' : '' }} >
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier['id'] }}"
                                                    checked>{{  $supplier['name']  }}</option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault" wire:model.live.debounce.600ms="isMultipleSupplier"
                                        >
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
                                            id="resupplyOutlet" wire:model.live="payment"
                                        {{ $isMultipleSupplier ? 'disabled' : '' }}>

                                        @foreach($paymentType as $payment)
                                            <option value="{{ $payment }}">{{ $payment }}</option>
                                        @endforeach

                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="container-input-default margin-top-24">
                            <div class="row align-items-end">

                                <div class="col-md-6">
                                    <label for="warehouseInput"
                                           class="form-label input-label">Tenggat pembayaran</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <select class="form-select input-default"
                                            id="paymentDateOption" wire:model.live="deadlinePayment"

                                        {{ $this->payment != 'NET' || $isMultipleSupplier ? 'disabled' : '' }}
                                    >
                                        <option value="3">3 Hari</option>
                                        <option value="7">7 Hari</option>
                                        <option value="14">14 Hari</option>
                                        <option value="30">30 Hari</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <div class="input date" data-provide="datepicker">
                                        <input type="date" id="datePayment"
                                               class="form-control input-default" {{ $this->payment != 'NET' || $isMultipleSupplier ? 'disabled' : '' }}>
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
                                   class="form-label input-label">Item pembelian</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                        </div>
                    </div>

                    {{--                    {{ json_encode($purchaseRequestDetail) }}--}}

                    <div class="col-sm-12">
                        <table id="" class="table borderless table-hover">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Item</th>
                                <th scope="col">Pemasok</th>
                                <th scope="col">Pembayaran</th>
                                <th scope="col">Tenggat</th>
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
                                @php

                                    $this->componentItems[] = [
                                        'isSelected' => 'false',
                                        'purchaseRequestId' => $purchaseRequests->id,
                                        'itemId' => $detail->item->id,
                                        'itemName' => $detail->item->name,
                                        'supplier' => $this->supplier,
                                        'payment' =>  $this->payment,
                                        'stockActual' => $purchaseRequests->reference->requestable->warehouse->warehouseItem->last()->stockItem->last()->qty_on_hand,
                                        'qtyBuy' => $detail->qty_buy,
                                        'unitName' => $detail->item->unit->name,
                                        'unitPrice' => number_format($purchaseRequests->reference->requestable->warehouse->warehouseItem->last()->stockItem->last()->avg_cost, 0),
                                        'purchaseAmount' =>  number_format($detail->qty_buy, 0),
                                        'totalAmount' => $detail->qty_buy * $purchaseRequests->reference->requestable->warehouse->warehouseItem->last()->stockItem->last()->avg_cost,
                                        'deadlinePayment' => '3',
                                    ];


                                  $this->componentItems[$key]['supplier'] = $this->supplier;
                                  $this->componentItems[$key]['payment'] = $this->payment;
                                  $this->componentItems[$key]['deadlinePayment'] = $this->deadlinePayment;

                                  if($key == $this->indexPayment) {
                                    $this->componentItems[$key]['payment'] = $this->paymentTemp;
                                    $this->indexPayment = '';
                                  }



                                @endphp


                                <tr wire:key="{{ $loop->iteration }}">
                                    <td>{{ $detail->item->name }}</td>
                                    <td>

                                        <select class="form-select dropdown-no-border"
                                                id="supplier"
                                                {{ $isMultipleSupplier != true ? 'disabled' : '' }} wire:model="componentItems.{{ $key }}.supplier">

                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier['id'] }}"
                                                        checked>{{  $supplier['name']  }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select dropdown-no-border"
                                                id="supplier" {{ !$isMultipleSupplier ? 'disabled' : '' }}
                                                wire:change="handleItemPaymentChange('{{ $key }}')"
                                                wire:model="componentItems.{{ $key }}.payment">
                                            @foreach($paymentType as $payment)
                                                <option value="{{ $payment }}">{{ $payment }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @if($this->componentItems[$key]['payment'] == 'NET')
                                            <select class="form-select input-default"
                                                    id="paymentDateOption"
                                                    wire:model="componentItems.{{$key}}.deadlinePayment"

                                                {{ $this->payment != 'NET' || !$isMultipleSupplier ? 'disabled' : '' }}
                                            >
                                                <option value="3">3 Hari</option>
                                                <option value="7">7 Hari</option>
                                                <option value="14">14 Hari</option>
                                                <option value="30">30 Hari</option>
                                            </select>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $this->componentItems[$key]['stockActual'] }}</td>
                                    <td>{{ $this->componentItems[$key]['qtyBuy'] }}</td>
                                    <td>{{ $this->componentItems[$key]['unitName'] }}</td>
                                    <td>
                                        <input type="text" class="form-control input-default"
                                               wire:model="componentItems.{{ $key }}.unitPrice"
                                               wire:change="handleValuePurchaseAmount('{{ $key }}')"
                                               x-mask:dynamic="$money($input)"
                                        >
                                    </td>
                                    <td>
                                        <input type="number" class="form-control input-default"
                                               wire:model="componentItems.{{ $key }}.purchaseAmount"
                                               wire:change="handleValuePurchaseAmount('{{ $key }}')">
                                    </td>
                                    <td>{{  IndonesiaCurrency::formatToRupiah($this->componentItems[$key]['totalAmount']) }}</td>
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

    <footer-script>
        <script src="{{ asset('js/purchase.js') }}"></script>
    </footer-script>

</x-page-layout>

