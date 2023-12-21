@php use Carbon\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail produksi
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-danger btn-nav margin-left-10"
                                @click="$dispatch('cancel-edit-warehouse')">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="acceptAndNext"
                    >Terima dan lanjutkan
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

            @if($status == 'Baru')
                <div class="col-sm-5 offset-1">
                    {{-- KODE REFERENSI --}}
                    <div>
                        <p class="subtitle-3-regular">Kode referensi</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">{{ $requestStock->code }}</p>
                    </div>


                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Tanggal</p>
                        <div id="divider" class="margin-top-6"></div>
                        <p class="margin-top-6 subtitle-3-medium">
                            {{ Carbon::createFromFormat('Y-m-d H:i:s', $requestStock->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}
                        </p>
                    </div>

                    <div class="margin-top-24">
                        <p class="subtitle-3-regular">Item produksi</p>
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

                            {{-- Gunakan eager loading untuk mengambil item terkait secara efisien --}}
                            @php
                                $requestStock->load('requestStockDetail.item');
                            @endphp
                            @foreach ($requestStock->requestStockDetail as $requestDetail)
                                <tr class="items-table-head-color" id="po1" style="cursor: pointer">
                                    <td>{{ $requestDetail->item->name }}</td>
                                    <td>{{ $requestDetail->qty }}</td>
                                    <td>{{ $requestDetail->item->unit->name }}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            @elseif($status == 'Produksi diterima')
                <div class="col-sm-5 offset-1">
                    <div class="container-input-default  margin-top-16">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode produksi</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput"
                               value="{{ $production->code }}" disabled>
                    </div>
                </div>

                <div class="col-sm-9 offset-1 margin-top-16 set-height-item-request">

                    @if(isset($components) && !empty($components))
                        {{ Log::info($components) }}

                        @foreach($components as $key => $component)
                            {{-- Looping item produksi yang diminta --}}
                            <div class="accordion" id="accordionExample" wire:key="{{ $key }}">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne{{ $component['item']['id'] }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="accordion{{ $component['item']['id'] }}"
                                                aria-expanded="true"
                                                aria-controls="accordion{{ $component['item']['id'] }}">
                                            {{ $component['item']['name'] }}

                                        </button>
                                    </h2>
                                    <div id="accordion{{ $component['item']['id'] }}"
                                         class="accordion-collapse collapse show"
                                         aria-labelledby="headingOne"
                                         data-bs-parent="#accordionExample">
                                        <div class="accordion-body">

                                            <table class="table-component table table-hover margin-top-16"
                                                   id="tableItemRequest"
                                            >
                                                <thead class="sticky-top">
                                                <tr>
                                                    <th>
                                                        <input class="form-check-input" type="checkbox" value=""
                                                               id="selectAllCheckbox"
                                                               wire:model="selectAll">
                                                    </th>
                                                    <th>Item</th>
                                                    <th>Jumlah Resep</th>
                                                    <th>Unit</th>
                                                    <th>Jumlah Diminta</th>
                                                    <th>Unit</th>
                                                </tr>
                                                </thead>

                                                <tbody>

                                                {{-- Looping isi resep dari item yang diminta --}}

                                                @if(isset($component['recipe']) && !empty($component['recipe']))

                                                    @foreach($component['recipe'] as $key =>  $recipe)
                                                        <tr wire:key="{{ $key }}">
                                                            <td>
                                                                <input class="form-check-input" type="checkbox"
                                                                       id="checkbox_{{ $loop->iteration }}">
                                                            </td>
                                                            <td>{{ $recipe['item_component_name'] }}</td>
                                                            <td>{{ $recipe['item_component_usage'] }}</td>
                                                            <td>{{ $recipe['item_component_unit'] }}</td>
                                                            <td>
                                                                <input type="text" class="form-control input-default"
                                                                       x-mask:dynamic="$money($input, '.')"
                                                                       value="{{ $recipe['item_component_usage'] }}">
                                                            </td>
                                                            <td>{{ $recipe['item_component_unit'] }}</td>
                                                        </tr>

                                                    @endforeach

                                                @endif


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        @endforeach

                    @endif


                </div>
            @endif
        </div>
    </div>


    <script>

    </script>


</x-page-layout>
