@php use Illuminate\Support\Facades\Storage; @endphp
<x-page-layout>
    <x-notify::notify/>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ __('app_locale.text.tambahGudang') }}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click.prevent="validateInput">{{ __('app_locale.button.simpan') }}</button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">
        <div class="row">
            <form x-data="{open: false}">
                <div class="col-sm-6 offset-1">

                    @if($notFound)
                        <h1>Warehouse atau outlet tidak ada</h1>
                    @else

                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plKodeGudang') }}"
                                   wire:model.live.debounce.600ms="codeWarehouse">
                            @error('codeWarehouse') <span class="error">{{ $message }}</span> @enderror

                        </div>


                        <div class="container-input-default  margin-top-16">
                            <label for="warehouseInput"
                                   class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plNamaGudang') }}"
                                   wire:model.live.debounce.600ms="nameWarehouse">
                            @error('nameWarehouse') <span class="error">{{ $message }}</span> @enderror

                        </div>


                        <div class="container-input-default margin-top-16">

                            <label for="warehouseInput"
                                   class="form-label input-label">{{ __('app_locale.text.areaGudang') }}</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <table id="areaGudangTable" class="table-component table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ __('app_locale.text.area') }}</th>
                                    <th>{{ __('app_locale.text.rak') }}</th>
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
                                                   wire:model.live="areas.{{$key}}.area.area">

                                            @if ($errors->has("areas.$key.area.area"))
                                                <span
                                                    class="text-xs text-red-600">{{ $errors->first("areas.$key.area.area") }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text"
                                                   class="input-no-border make-input rackInput caption-medium"
                                                   placeholder="A1" style="width: 100%"
                                                   wire:model="areas.{{$key}}.area.rack">
                                            @if ($errors->has("areas.$key.area.rack"))
                                                <span
                                                    class="text-xs text-red-600">{{ $errors->first("areas.$key.area.rack") }}</span>
                                            @endif
                                        </td>

                                        <td class="delete-item" style="width: 16px"
                                            wire:click.prevent="remove( {{ $key }})">
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
                                                <td class="delete-item"
                                                    wire:click.prevent="removeRack({{ $key }}, {{ $subKey }})">
                                                    <i class="x-icon"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach


                                <tr id="addWarehouseAction">
                                    <td>
                                        <button class="btn icon-text caption-medium" type="button" id="addArea"
                                                wire:click.prevent="addArea" wire:loading.attr="disabled">
                                            + Area
                                        </button>
                                    </td>
                                    <td>

                                        <button class="btn icon-text caption-medium" type="button" id="addRack"
                                                wire:click="addRack"
                                                wire:loading.attr="disabled"
                                                style="display: {{ ($isAddedArea) ? 'block' : 'none' }}">
                                            + Rak
                                        </button>
                                    </td>
                                    <td></td>

                                </tr>
                                </tbody>
                            </table>


                            <div class="margin-top-16">
                                <label for="addressWarehouse" class="form-label">Alamat</label>
                                <div id="divider" class="margin-symmetric-vertical-6"></div>
                                <textarea class="form-control textarea" id="addressWarehouse" rows="5"
                                          placeholder="Jln. Ki Hajar Dewantoro"
                                          wire:model.blur="addressWarehouse"></textarea>
                                @error('addressWarehouse') <span class="error">{{ $message }}</span> @enderror
                            </div>


                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div wire:ignore.self class="modal modal-input fade" data-bs-backdrop="static" id="modalItem" tabindex="-1"
         role="dialog"
    >

        @if(!$isShowModalNewItem)
            <!-- Konten modal Item -->
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                        <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                            {{ __('app_locale.modal.modalTambahGudang') }}</h1>
                    </div>
                    <div class="modal-body
                ">
                        <div wire:loading>
                            <div class="position-absolute start-50 translate-middle">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <form class="d-flex ">
                            <input class="form-control full-search-bar clear container-fluid" type="search"
                                   placeholder="Search"
                                   aria-label="Search">
                        </form>

                        {{-- Item baru ditambahkan --}}
                        @if(!empty($item))
                            <p class="subtitle-3-medium" style="margin-top: 24px">Item baru ditambahkan</p>
                            <div id="divider"></div>
                            <div class="items-modal">
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
                                    <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                           wire:click="selectItem('{{ $item['id'] }}', '{{ $item['name'] }}')"/>
                                </div>
                            </div>
                            <div id="divider" class="margin-top-16"></div>

                            <p class="subtitle-3-medium" style="margin-top: 24px;">Daftar item</p>
                            <div id="divider" class="margin-top-16"></div>
                        @endif


                        @if(isset($items) && !empty($items))
                            @foreach($items['data'] as $data)
                                <div class="items-modal">
                                    <div class="d-flex align-items-center data-modal">

                                        <div
                                            class="item-modal d-flex flex-row align-items-center justify-content-between">

                                            <div class="d-flex flex-row align-items-center">
                                                <img class="items-ingredient-img"
                                                     src="{{ ($data['image'] == null) ? asset('img/no-image.png') : asset("storage/item-image/{$data['image']}") }}"
                                                     alt="">

                                                <div
                                                    class="body-text-regular name-item-modal">{{ $data['name'] }}</div>
                                            </div>
                                            <div>


                                                @if(isset($data['checked'])&& $data['checked'])
                                                    {{-- Pengecekan untuk checbox yang diklik oleh item yang sama, jika sama maka ada kemungkinan untuk menghapus item yang ditambahkan --}}
                                                    {{-- Pengecekan jika dibuka oleh area  --}}

                                                    @if(isset($data['from']) && $this->rack == '' && $data['from'] == 'area' && $data['indexArea'] == $this->area)
                                                        {{-- Pengecekan untuk checbox yang diklik oleh area yang sama --}}

                                                        <input id="itemCheckBox" class="red-input checkbox"
                                                               type="checkbox"
                                                               checked
                                                               wire:click="removeCheckboxArea('{{ $data['id'] }}', '{{ $data['indexArea'] }}')"/>
                                                    @elseif(isset($data['from'])  && $data['from'] == 'rack' && $data['indexArea'] == $this->area  && $data['indexRack'] == $this->rack)
                                                        {{-- Pengecekan untuk checbox yang diklik oleh rack yang sama --}}
                                                        <input id="itemCheckBox" class="red-input checkbox"
                                                               type="checkbox"
                                                               checked
                                                               wire:click="removeCheckboxRack('{{ $data['id'] }}', '{{ $data['indexArea'] }}', '{{ $data['indexRack'] }}')"/>
                                                    @else

                                                        {{-- Pengecekan untuk checbox yang diklik oleh area yang sama --}}
                                                        <input id="itemCheckBox" class="red-input checkbox"
                                                               type="checkbox"
                                                               checked
                                                               disabled/>
                                                    @endif

                                                @else
                                                    <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                                           wire:click="selectItem('{{ $data['id'] }}', '{{ $data['name'] }}')"/>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div id="divider" class="margin-top-20"></div>
                                </div>

                            @endforeach
                            <div wire:loading>
                                <div class="position-absolute start-50 translate-middle">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        @endif


                    </div>
                    <div class="modal-footer row">

                        <div class=" d-flex flex-row justify-content-end">


                            <div>
                                <button class="btn text-only-outlined cancel-btn" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button class="btn btn-text-only-primary margin-left-10"
                                        wire:click="openModalNewItem">Buat
                                    item baru
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        @else
            <!-- Konten modal tambah item -->
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                        <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                            Buat item baru</h1>
                    </div>
                    <div class="modal-body" id="modalBodyNewItem">

                        {{-- Item Image --}}
                        <div class="d-flex flex-column align-self-center align-items-center">

                            @if ($photoNewItem && !$errors->has('photoNewItem'))
                                <img src="{{ $photoNewItem->temporaryUrl() }}" width="140">
                            @else
                                <img
                                    src="https://s3-alpha-sig.figma.com/img/473a/648b/8c5e12d4ea9042ba824140de2d5e468c?Expires=1701043200&Signature=moY~rAa-4k3hCaxWn0HH5HUSCTDSJUZ-tUeoTLMjVcWtS-riZIbl0-0iyaxyf-RqEkmqdRhobrWqoq5164K7-NGlb5f2eWX-SDm1UTG7RiP00IunvGFzKxVTg63z-d0HSWw-s4G60zZ1XoF41GGxRCwTFUemvx4TpS~3t3evAqnG6z1wRfSmsxKEChWfVfpIv2zGOGEtJ4jhK-QyWoPzGU5g~XaBWzvpCF67OH0wCPd3e9P52YQCFzaPhsRB2Kiap~-9pucACptxt14Q6JOszManxnflqb2DbFlaz3iqS~ta9y8rY-3Tw2~332CmzwDxX-ltN3fWF8VZ7eqVcW1qCg__&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4"
                                    alt="" srcset="" width="140">
                            @endif
                            <div wire:loading wire:target.prevent="photoNewItem">Uploading...</div>
                            @error('photoNewItem') <span class="error">{{ $message }}</span> @enderror

                            <div>
                                <button type="button" class="btn btn-icon-text-outlined margin-top-16"
                                        onclick="document.getElementById('file-input').click();">
                                    <i class="pencil-icon"></i>
                                    Pilih foto
                                </button>
                                <input type="file" id="file-input" wire:model="photoNewItem"
                                       style="display: none;"
                                       accept="image/*">
                            </div>

                        </div>


                        {{--Kode Item  --}}
                        <div class=" container-input-default margin-top-16">
                            <label for="codeItem"
                                   class="form-label input-label">Kode item</label>

                            <input type="name" class="form-control input-default"
                                   id="codeItem" placeholder="BMDGSP01" wire:model.blur="codeItem"
                            >
                            @error('codeItem') <span class="error">{{ $message }}</span> @enderror

                        </div>

                        <div id="divider" class="margin-top-20"></div>

                        {{-- Nama  --}}
                        <div class="container-input-default margin-top-20">
                            <label for="nameItem"
                                   class="form-label input-label">Nama</label>

                            <input type="name" class="form-control input-default"
                                   id="nameItem" placeholder="Daging sapi" wire:model.blur="nameItem"
                            >
                            @error('nameItem') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div id="divider" class="margin-top-20"></div>


                        {{-- TODO: buat pencarian checkbox berjalan --}}
                        <div class="margin-top-20">
                            <label for="dropdownCategory" class="form-label input-label">Kategori</label>
                            <div class="dropdown" id="dropdownCategory">
                                <button class="btn dropdown-toggle dropdown-default" type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        wire:click="loadCategory"
                                >
                                    {{ $categoryName == null ? 'Kategori' : $categoryName }}
                                </button>


                                <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;"
                                    wire:ignore.self>
                                    <input type="text" placeholder="Cari kategori"
                                           style="margin: 6px;">
                                    @if(empty($categoryItems))
                                        <div wire:loading>
                                            <div class="position-absolute start-50 translate-middle">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        @foreach($categoryItems as $categoryItem)
                                            <li><a class="dropdown-item"
                                                   wire:click="selectCategory('{{ $categoryItem->id }}', '{{ $categoryItem->name }}' )">{{ $categoryItem->name }}</a>
                                            </li>
                                        @endforeach

                                    @endif

                                </ul>
                            </div>
                            @error('categoryName') <span class="error">{{ $message }}</span> @enderror
                        </div>


                        <div id="divider" class="margin-top-20"></div>

                        <div class="margin-top-20">
                            <label for="description" class="form-label">Deskripsi item</label>
                            <textarea class="form-control textarea" id="description" rows="5"
                                      placeholder="Daging sapi ayam segar"
                                      wire:model.blur="description"></textarea>
                        </div>


                        {{--                        <div wire:loading>--}}
                        {{--                            <div class="position-absolute start-50 translate-middle">--}}
                        {{--                                <div class="spinner-border" role="status">--}}
                        {{--                                    <span class="visually-hidden">Loading...</span>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}


                    </div>
                    <div class="modal-footer row">
                        <div class=" d-flex flex-row justify-content-end">

                            <div>
                                <button class="btn text-only-outlined cancel-btn"
                                        wire:click="cancelNewItem">Batal
                                </button>
                                <button class="btn btn-text-only-primary margin-left-10"
                                        wire:click="saveNewItem" wire:loading.attr="disabled">Simpan
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>


</x-page-layout>

@section('footer-script')
    <script src="{{ asset('js/add-warehouse.js') }}"></script>
@endsection



