<div id="content-loaded">
    {{ $urlQuery }}

    <form x-data="{open: false}">
        @if($warehouse !== null)

            {{-- MODE VIEW --}}
            @if($mode == 'view')
                <div class="col-sm-6 offset-1">
                    {{-- KODE GUDANG --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>

                        <input type="name" class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->warehouse_code }}" disabled>
                    </div>

                    {{-- NAMA GUDANG --}}
                    <div class="container-input-default margin-top-24
                ">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>

                        <input type="name" class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->name }}" disabled>
                    </div>

                    {{-- NAMA GUDANG --}}
                    <div class="container-input-default margin-top-24
                ">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>

                        <input type="name" class="form-control input-default" id="warehouseInput"
                               value="{{ $warehouse->name }}" disabled>
                    </div>

                    {{-- AREA GUDANG --}}


                </div>


                {{-- MODE EDIT --}}
            @else
                <div class="col-sm-6 offset-1">
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>


                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plKodeGudang') }}"
                               wire:model.blur="codeWarehouse">
                        @error('codeWarehouse') <span class="error">{{ $message }}</span> @enderror

                    </div>


                    <div class="container-input-default  margin-top-24">


                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plNamaGudang') }}"
                               wire:model.blur="nameWarehouse">
                        @error('nameWarehouse') <span class="error">{{ $message }}</span> @enderror

                    </div>


                    <div class="container-input-default  margin-top-24">
                        <label for="dropdownCategory" class="form-label input-label">Lokasi gudang</label>
                        <div class="dropdown" id="dropdownCategory">
                            <button class="btn dropdown-toggle dropdown-default" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                {{ ($locationWarehouse == null) ? 'Pilih lokasi' : $locationWarehouse['name'] }}
                            </button>

                            <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                <input type="text" placeholder="Cari outlet" style="margin: 6px; width: 95%;">


                                <li><a class="dropdown-item"
                                       wire:click="setLocationWarehouse('default', 'Gudang pusat')">Gudang
                                        pusat</a></li>
                            </ul>
                        </div>
                        @error('locationWarehouse') <span class="error">{{ $message }}</span> @enderror
                    </div>


                    <div class="container-input-default margin-top-24">

                        <label for="warehouseInput"
                               class="form-label input-label">{{ __('app_locale.text.areaGudang') }}</label>


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
                                        <input type="text" class="input-no-border make-input areaInput caption-medium"
                                               placeholder="Area A" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.area">
                                        @if ($errors->has("areas.$key.area.area"))
                                            <span
                                                class="text-xs text-red-600">{{ $errors->first("areas.$key.area.area") }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input rackInput caption-medium"
                                               placeholder="A1" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.rack">
                                        @if ($errors->has("areas.$key.area.rack"))
                                            <span
                                                class="text-xs text-red-600">{{ $errors->first("areas.$key.area.rack") }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="input-no-border make-input catInvInput caption-medium"
                                               placeholder="Bahan mentah" style="width: 100%"
                                               wire:model="areas.{{$key}}.area.category_inventory">
                                        @if ($errors->has("areas.$key.area.category_inventory"))
                                            <span
                                                class="text-xs text-red-600">{{ $errors->first("areas.$key.area.category_inventory") }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn icon-text" type="button" id="addItem" data-bs-toggle="modal"
                                                data-bs-target="#modalItem"
                                                @click="$dispatch('load-modal', {area: {{ $key }} })"
                                                style="width: 120px; text-align: start;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                        >
                                            @if(empty($area['area']['item']))
                                                + Item
                                            @else
                                                @foreach($area['area']['item'] as $text )
                                                    {{ $text['name'] }},
                                                @endforeach
                                            @endif
                                        </button>
                                    </td>
                                    <td class="delete-item" wire:click.prevent="remove( {{ $key }})">
                                        <i class="x-icon"></i>
                                    </td>
                                </tr>

                                @if(isset($area['rack']))
                                    @foreach($area['rack'] as $subKey => $value)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text"
                                                       class="input-no-border make-input rackInput caption-medium"
                                                       placeholder="A1" style="width: 100%"
                                                       wire:model="areas.{{ $key }}.rack.{{ $subKey }}.rack">
                                                @if ($errors->has("areas.$key.rack.$subKey.rack"))
                                                    <span
                                                        class="text-xs text-red-600">{{ $errors->first("areas.$key.rack.$subKey.rack") }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="input-no-border make-input catInvInput caption-medium"
                                                       placeholder="Bahan mentah" style="width: 100%"
                                                       wire:model="areas.{{$key}}.rack.{{ $subKey }}.category_inventory">
                                                @if ($errors->has("areas.$key.rack.$subKey.category_inventory"))
                                                    <span
                                                        class="text-xs text-red-600">{{ $errors->first("areas.$key.rack.$subKey.category_inventory") }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn icon-text" type="button" id="addItem"
                                                        data-bs-toggle="modal" data-bs-target="#modalItem"
                                                        @click="$dispatch('load-modal-rack', {area: {{ $key  }}, rack: {{ $subKey }} })"
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
                                            <td class="delete-item"
                                                wire:click.prevent="removeRack({{ $key }}, {{ $subKey }})">
                                                <i class="x-icon"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach


                            {{--                        <tr id="addWarehouseAction">--}}
                            {{--                            <td>--}}
                            {{--                                <button class="btn icon-text caption-medium" type="button" id="addArea"--}}
                            {{--                                        wire:click.prevent="addArea">--}}
                            {{--                                    + Area--}}
                            {{--                                </button>--}}
                            {{--                            </td>--}}
                            {{--                            <td>--}}

                            {{--                                <button class="btn icon-text caption-medium" type="button" id="addRack"--}}
                            {{--                                        wire:click="addRack"--}}
                            {{--                                        style="display: {{ ($isAddedArea) ? 'block' : 'none' }}">--}}
                            {{--                                    + Rak--}}
                            {{--                                </button>--}}
                            {{--                            </td>--}}
                            {{--                            <td></td>--}}
                            {{--                            <td></td>--}}
                            {{--                            <td></td>--}}
                            {{--                        </tr>--}}
                            </tbody>
                        </table>


                        <div class="margin-top-24">
                            <label for="addressWarehouse" class="form-label">Alamat</label>
                            <textarea class="form-control textarea" id="addressWarehouse" rows="5"
                                      placeholder="Jln. Ki Hajar Dewantoro"
                                      wire:model.blur="addressWarehouse"></textarea>
                            @error('addressWarehouse') <span class="error">{{ $message }}</span> @enderror
                        </div>


                    </div>
            @endif
    </form>
    @endif
</div>
