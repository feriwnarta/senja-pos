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
                    <div class="container-input-default margin-top-24">
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

                                <tr>
                                    <td>
                                        <input type="text" class="input-no-border make-input areaInput caption-medium"
                                               placeholder="Area A" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.area" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input rackInput caption-medium"
                                               placeholder="A1" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.racks.0.name" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input catInvInput caption-medium"
                                               placeholder="Bahan mentah" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.racks.0.category_inventory" disabled>
                                    </td>

                                    <td>

                                        <button class="btn icon-text" type="button" id="addItem" data-bs-toggle="modal"
                                                data-bs-target="#modalDetailItem"
                                                @click="$dispatch('detail-item-rack', {id: '{{ ($area['area']['racks'][0]['id']) ?? '' }}' })"
                                                style="width: 120px; text-align: start;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            @forelse($area['area']['racks'][0]['item'] ?? [] as $item)
                                                {{ $item['name'] }}
                                            @empty
                                                Item belum ditambahkan
                                            @endforelse
                                        </button>
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
                                                        @click="$dispatch('detail-item-rack', {id: '{{ $value['id'] }}' })"
                                                        style="width: 120px; text-align: start;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"

                                                >
                                                    @if(empty($value['item']))
                                                        Item belum ditambahkan
                                                    @else
                                                        @foreach($value['item'] as $item )
                                                            {{ $item['name'] }},
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


                        {{-- ALAMAT --}}
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
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>
                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                        <input type="name" class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->name }}" wire:model.live.debounce.600ms="warehouseName">

                        @error('warehouseName') <span text-xs text-red-600>{{ $message }}</span> @enderror
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
                                <th></th>
                            </tr>
                            </thead>

                            <tbody id="warehouseData">

                            @foreach($areas as $key => $area)

                                <tr>
                                    <td>
                                        <input type="text"
                                               class="input-no-border make-input areaInput caption-medium"
                                               placeholder="Area A" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.area"
                                        >

                                        @error("areas.$key.area.area")
                                        <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </td>

                                    <td>
                                        <input type="text" class="input-no-border make-input rackInput caption-medium"
                                               placeholder="A1" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.racks.0.name">
                                        @error("areas.$key.area.racks.0.name")
                                        <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input catInvInput caption-medium"
                                               placeholder="Bahan mentah" style="width: 100%"
                                               wire:model="areas.{{ $key }}.area.racks.0.category_inventory">
                                        @error("areas.$key.area.racks.0.category_inventory")
                                        <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <button class="btn icon-text hoy" type="button"
                                                id="{{ ($area['area']['racks'][0]['id']) ?? '' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditItem"
                                                @click="$dispatch('detail-item-rack-edit', {id: '{{ ($area['area']['racks'][0]['id']) ?? '' }}' })"
                                                style="width: 120px; text-align: start;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            @forelse($area['area']['racks'][0]['item'] ?? [] as $item)
                                                {{ $item['name'] }}
                                            @empty
                                                Item belum ditambahkan
                                            @endforelse
                                        </button>
                                    </td>
                                    <td class="delete-item" wire:click.prevent="removeArea({{ $key }})">
                                        <i class="x-icon"></i>
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
                                                >
                                                @if ($errors->has("areas.$key.area.racks.$subKey.name"))
                                                    <span
                                                        class="text-xs text-red-600">{{ $errors->first("areas.$key.area.racks.$subKey.name") }}</span>
                                                @endif

                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="input-no-border make-input catInvInput caption-medium"
                                                       placeholder="Bahan mentah" style="width: 100%"
                                                       wire:model.live="areas.{{ $key }}.area.racks.{{ $subKey }}.category_inventory"
                                                >
                                                @if ($errors->has("areas.$key.area.racks.$subKey.category_inventory"))
                                                    <span
                                                        class="text-xs text-red-600">{{ $errors->first("areas.$key.area.racks.$subKey.category_inventory") }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn icon-text" type="button" id="addItem"
                                                        data-bs-toggle="modal" data-bs-target="#modalEditItem"
                                                        @click="$dispatch('detail-item-rack-edit', {id: '{{ $area['area']['racks'][$subKey]['id'] }}' })"
                                                        style="width: 120px; text-align: start; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                >

                                                    @if(empty($value['item']))
                                                        Item belum ditambahkan
                                                    @else
                                                        @foreach($value['item'] as $item )
                                                            {{ $item['name'] }},
                                                        @endforeach
                                                    @endif
                                                </button>
                                            </td>
                                            <td class="delete-item"
                                                wire:click.prevent="removeAreaRack({{ $key }}, {{ $subKey }})">
                                                <i class="x-icon"></i>
                                            </td>

                                        </tr>

                                    @endif
                                @endforeach
                            @endforeach

                            {{-- BUTTON ACTION ADD RACK / AREA --}}
                            <tr id="addWarehouseAction">
                                <td>
                                    <button class="btn icon-text caption-medium" type="button" id="addArea"
                                            wire:click="addNewArea">
                                        + Area
                                    </button>
                                </td>
                                <td>
                                    <button class="btn icon-text caption-medium" type="button" id="addRack"
                                            wire:click="addNewRack">
                                        + Rak
                                    </button>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>


                        {{-- ALAMAT --}}
                        <div class="margin-top-24">
                            <label for="addressWarehouse" class="form-label">Alamat</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="addressWarehouse" rows="5"
                                      wire:model.live.debounce.600ms="warehouseAddress"></textarea>
                            @if ($errors->has("warehouseAddress"))
                                <span
                                    class="text-xs text-red-600">{{ $errors->first("warehouseAddress") }}</span>
                            @endif
                        </div>


                    </div>
            @endif


    </form>
    @endif

    {{-- MODAL DETAIL ITEM --}}
    <div wire:ignore.self class="modal modal-input fade" id="modalDetailItem" tabindex="-1"
         role="dialog"
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


                    {{-- TODO: Buat fungsi pencarian item --}}
                    <form class="d-flex ">
                        <input class="form-control full-search-bar clear container-fluid" type="search"
                               placeholder="Search"
                               aria-label="Search">
                    </form>

                    {{-- ITEM --}}
                    <div class="item-modal">
                        @foreach($itemData as $item)
                            <div class="d-flex align-items-center data-modal">
                                <div class="item-modal d-flex flex-row align-items-center">
                                    <div>
                                        <img class="items-ingredient-img"
                                             src="{{ ($item['item_image'] == null) ? asset('img/no-image.png') : asset("storage/item-image/{$item['item_image']}") }}"
                                             alt="">
                                    </div>
                                    <div
                                        class="body-text-regular name-item-modal">{{ $item['name'] }}</div>
                                </div>
                            </div>

                            <div id="divider" class="margin-top-16"></div>
                        @endforeach
                    </div>


                    <div wire:loading>
                        <div class="position-absolute start-50 translate-middle">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer row">

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT ITEM --}}
    <div wire:ignore.self class="modal modal-input fade" id="modalEditItem" tabindex="-1"
         role="dialog"
    >
        <!-- Konten modal -->
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                    <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                        {{-- TODO: Buat title ini dinamis --}}
                        Daftar Item</h1>
                </div>
                <div class="modal-body">


                    {{-- TODO: Buat fungsi pencarian item --}}
                    <form class="d-flex ">
                        <input class="form-control full-search-bar clear container-fluid" type="search"
                               placeholder="Search"
                               aria-label="Search">
                    </form>

                    {{-- ITEM --}}
                    <div class="item-modal">
                        @foreach($itemEditData as $item)
                            <div class="d-flex align-items-center data-modal">
                                <div class="item-modal d-flex flex-row align-items-center">
                                    <div>
                                        <img class="items-ingredient-img"
                                             src="{{ ($item['item_image'] == null) ? asset('img/no-image.png') : asset("storage/item-image/{$item['item_image']}") }}"
                                             alt="">
                                    </div>
                                    <div
                                        class="body-text-regular name-item-modal">{{ $item['name'] }}</div>

                                </div>

                                @if($item['checked'] == 'true')
                                    <input id="{{ $item['id'] }}" data-meta="{{ $item['name'] }}"
                                           class="red-input checkbox" type="checkbox" checked/>
                                @else

                                    {{-- JIKA ITEM TIDAK CHECKED --}}
                                    <input id="{{ $item['id'] }}" data-meta="{{ $item['name'] }}"
                                           class="red-input checkbox"
                                           type="checkbox" {{ (isset($item['disabled'])) ? 'disabled checked' : '' }}/>
                                @endif
                            </div>

                            <div id="divider" class="margin-top-16"></div>
                        @endforeach
                    </div>


                    <div wire:loading>
                        <div class="position-absolute start-50 translate-middle">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer row">

                </div>
            </div>
        </div>
    </div>


</div>







