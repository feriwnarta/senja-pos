<div id="content-loaded">


    <form>

        @if($htmlCondition != '')
            <h1> {{ $htmlCondition }}</h1>
        @endif



        @if($warehouse !== null && $htmlCondition == '')



            {{-- MODE VIEW --}}
            @if($mode == 'view')
                <div class="col-sm-6 offset-1">
                    {{-- KODE GUDANG --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>
                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type=" name
                        " class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->warehouse_code }}" disabled>
                    </div>

                    {{-- NAMA GUDANG --}}
                    <div class="container-input-default margin-top-24
                ">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>
                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                        <input type="name" class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->name }}" disabled>
                    </div>


                    {{-- AREA GUDANG --}}
                    <div class="container-input-default margin-top-24">

                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.areaGudang') }}</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <table id="areaGudangTable" class="table-component table table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('app_locale.text.area') }}</th>
                                <th>{{ __('app_locale.text.rak') }}</th>
                                <th>{{ __('app_locale.text.kategoriInventory') }}</th>
                                <th>{{ __('app_locale.text.item') }}</th>
                            </tr>
                            </thead>

                            <tbody id="warehouseData">

                            @foreach($areas as $key => $area)

                                {{ \Illuminate\Support\Facades\Log::info($area) }}
                                <tr>
                                    <td>
                                        <input type="text" class="input-no-border make-input areaInput caption-medium"
                                               placeholder="Area A" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.area" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input rackInput caption-medium"
                                               placeholder="A1" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.racks.0.name" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input catInvInput caption-medium"
                                               placeholder="Bahan mentah" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.racks.0.category_inventory" disabled>

                                    </td>
                                    <td>
                                        @foreach($area['area']['racks'] as $racks)
                                            <p class="caption-medium">{{ (empty($racks['item'])) ? 'item belum ditambahkan' : '' }}</p>

                                            @foreach($racks['item'] as $item)

                                            @endforeach
                                        @endforeach
                                    </td>

                                </tr>


                                @foreach($area['area']['racks'] as $subKey => $value)

                                    @if($subKey != 0)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text"
                                                       class="input-no-border make-input rackInput caption-medium"
                                                       placeholder="A1" style="width: 100%"
                                                       wire:model="areas.{{ $key }}.area.racks.{{ $subKey }}.name"
                                                       disabled>

                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="input-no-border make-input catInvInput caption-medium"
                                                       placeholder="Bahan mentah" style="width: 100%"
                                                       wire:model="areas.{{ $key }}.area.racks.{{ $subKey }}.category_inventory"
                                                       disabled>
                                                @if ($errors->has("areas.$key.rack.$subKey.category_inventory"))
                                                    <span
                                                        class="text-xs text-red-600">{{ $errors->first("areas.$key.rack.$subKey.category_inventory") }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn icon-text" type="button" id="addItem"
                                                        data-bs-toggle="modal" data-bs-target="#modalDetailItem"
                                                        @click="$dispatch('detail-item')"
                                                        style="width: 120px; text-align: start;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"

                                                >
                                                    @if(empty($value['item']))
                                                        + Item
                                                    @else
                                                        @foreach($value['item'] as $text )
                                                            {{ $text['name'] }},
                                                        @endforeach
                                                    @endif
                                                </button>
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach

                            </tbody>
                        </table>


                        <div class="margin-top-24">
                            <label for="addressWarehouse" class="form-label">Alamat</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="addressWarehouse" rows="5"
                                      placeholder="{{ $warehouse->address }}" disabled></textarea>
                        </div>


                    </div>

                </div>


                {{-- MODE EDIT --}}
            @else

            @endif


            {{-- MODAL DETAIL ITEM --}}
            <div class="modal modal-input" id="modalDetailItem" tabindex="-1" role="dialog"
            >
                <!-- Konten modal -->
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                            <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                                {{-- TODO: Buat title ini dinamis --}}
                                Daftar Item Gudang</h1>
                        </div>
                        <div class="modal-body">
                            <div wire:loading>
                                <div class="position-absolute start-50 translate-middle">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>

                            {{-- TODO: Buat fungsi pencarian item --}}
                            <form class="d-flex ">
                                <input class="form-control full-search-bar clear container-fluid" type="search"
                                       placeholder="Search"
                                       aria-label="Search">
                            </form>


                            @if(isset($areas) && $areas != null)
                                @foreach($areas as $area)
                                    <p class="subtitle-3-medium margin-top-24">Daftar Item</p>
                                    <div id="divider"></div>
                                    <div class="items-modal">

                                        @foreach($area['area']['racks'] as $rack)
                                            @foreach($rack['item'] as $item)
                                                <div class="d-flex align-items-center data-modal">
                                                    <div class="item-modal d-flex flex-row align-items-center">
                                                        <div>
                                                            <img class="items-ingredient-img"
                                                                 src="https://media.istockphoto.com/id/1282866808/id/foto/ayam-mentah-segar.jpg?s=612x612&w=0&k=20&c=qcxOlEFxGkAU2G-Mejj_6Uo813qTmMixcXNXbG5plj0="
                                                                 alt="">
                                                        </div>
                                                        <div
                                                            class="body-text-regular name-item-modal">{{ $item['name'] }}</div>
                                                    </div>
                                                </div>

                                                <div id="divider" class="margin-top-16"></div>
                                            @endforeach
                                        @endforeach

                                    </div>

                                @endforeach
                            @endif


                        </div>
                        <div class="modal-footer row">

                        </div>
                    </div>
                </div>
            </div>
    </form>
    @endif
</div>
