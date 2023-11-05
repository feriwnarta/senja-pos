<x-page-layout>

    <x-slot name="sidebar">
        <div wire:ignore>
            <livewire:components.sidebar.sidebar/>
        </div>
    </x-slot>

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


                            <tr id="addWarehouseAction">
                                <td>
                                    <button class="btn icon-text caption-medium" type="button" id="addArea"
                                            wire:click.prevent="addArea">
                                        + Area
                                    </button>
                                </td>
                                <td>

                                    <button class="btn icon-text caption-medium" type="button" id="addRack"
                                            wire:click="addRack"
                                            style="display: {{ ($isAddedArea) ? 'block' : 'none' }}">
                                        + Rak
                                    </button>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
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

                </div>
            </form>
        </div>
    </div>
    <div class="modal modal-input" id="modalItem" tabindex="-1" role="dialog"
         style="display: {{ ($isShow) ? 'block' : 'none'  }}">
        <!-- Konten modal -->
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

                    @if(isset($items) && !empty($items))
                        @foreach($items['data'] as $data)
                            <div class="items-modal">
                                <div class="d-flex align-items-center data-modal">

                                    <div class="item-modal d-flex flex-row align-items-center">
                                        <div>
                                            <img class="items-ingredient-img"
                                                 src="https://media.istockphoto.com/id/1282866808/id/foto/ayam-mentah-segar.jpg?s=612x612&w=0&k=20&c=qcxOlEFxGkAU2G-Mejj_6Uo813qTmMixcXNXbG5plj0="
                                                 alt="">
                                        </div>
                                        <div
                                            class="body-text-regular name-item-modal">{{ $data['name'] }}</div>

                                    </div>



                                    @if($data['checked'])
                                        {{-- Pengecekan untuk checbox yang diklik oleh item yang sama, jika sama maka ada kemungkinan untuk menghapus item yang ditambahkan --}}
                                        {{-- Pengecekan jika dibuka oleh area  --}}

                                        @if(isset($data['from']) && $this->rack == '' && $data['from'] == 'area' && $data['indexArea'] == $this->area)
                                            {{-- Pengecekan untuk checbox yang diklik oleh area yang sama --}}

                                            <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                                   checked
                                                   wire:click="removeCheckboxArea('{{ $data['id'] }}', '{{ $data['indexArea'] }}')"/>
                                        @elseif(isset($data['from'])  && $data['from'] == 'rack' && $data['indexArea'] == $this->area  && $data['indexRack'] == $this->rack)
                                            {{-- Pengecekan untuk checbox yang diklik oleh rack yang sama --}}
                                            <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                                   checked
                                                   wire:click="removeCheckboxRack('{{ $data['id'] }}', '{{ $data['indexArea'] }}', '{{ $data['indexRack'] }}')"/>
                                        @else

                                            {{-- Pengecekan untuk checbox yang diklik oleh area yang sama --}}
                                            <input id="itemCheckBox" class="red-input checkbox" type="checkbox" checked
                                                   disabled/>
                                        @endif

                                    @else
                                        <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                               wire:click="selectItem('{{ $data['id'] }}', '{{ $data['name'] }}')"/>
                                    @endif


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
                            <button class="btn text-only-outlined cancel-btn">Batal</button>
                            <button class="btn btn-text-only-primary margin-left-10" data-bs-toggle="modal"
                                    data-bs-target="#modalNewItem" wire:click="openModalNewItem">Buat
                                item baru
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    {{-- Modal new Item--}}
    <div class="modal modal-input" data-bs-backdrop="static" id="modalNewItem" tabindex="-1" role="dialog"
         style="display: {{ ($isShowModalNewItem) ? 'block' : 'none'  }}">
        <!-- Konten modal -->
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                    <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                        Buat item baru</h1>
                </div>
                <div class="modal-body" id="modalBodyNewItem">


                    <div wire:loading>
                        <div class="position-absolute start-50 translate-middle">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>


                    {{-- Item Image --}}
                    <div class="d-flex flex-column align-self-center align-items-center">

                        @if ($photoNewItem && !$errors->has('photoNewItem'))
                            <img src="{{ $photoNewItem->temporaryUrl() }}" width="140">
                        @else
                            <img
                                src="https://s3-alpha-sig.figma.com/img/473a/648b/8c5e12d4ea9042ba824140de2d5e468c?Expires=1699833600&Signature=EclP9HAIqnSZLSrsPTG0K-lULYxs1PeeTyg3ONhqnWBzRDFM7m6u~1NhNd7iBIcvn5p2rRl-0NjYrMRJ0m6CZYfhNGmRGy764Zp06aJORkBjk5ZU47eXuL664~KaS~mMLLbhvPQK9d9SNr47-eG9gP0xPa25lp2ZxY5z9Om0UR8IOgYqTW1lR-36rj-vxgTpURMtXug4fjqinvjTQPHhpMqwrgYQe2QtbR53jcXJvNspvraRVcXgQThVasNXhn4rahm~GkRIj35FaQwJyhVF2wTvN~wlVjXh0toIMNYNJZg0vGOqmrP-r3I5H8ZPtxOs5cK43wH7e5d7jpLZocDydg__&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4"
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
                            <input type="file" id="file-input" wire:model="photoNewItem" style="display: none;"
                                   accept="image/*">
                        </div>

                    </div>


                    {{--Kode Item  --}}
                    <div class=" container-input-default margin-top-24">
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
                               id="nameItem" placeholder="Daging sapi"
                        >
                    </div>

                    <div id="divider" class="margin-top-20"></div>


                    <div class="margin-top-20">
                        <label for="dropdownCategory" class="form-label input-label">Kategori</label>
                        <div class="dropdown" id="dropdownCategory">
                            <button class="btn dropdown-toggle dropdown-default" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                Kategori
                            </button>

                            <ul class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                <input type="text" placeholder="Cari kategori" style="margin: 6px; width: 95%;">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                            </ul>
                        </div>
                    </div>


                    <div id="divider" class="margin-top-20"></div>

                    <div class="margin-top-20">
                        <label for="description" class="form-label">Deskripsi item</label>
                        <textarea class="form-control textarea" id="description" rows="5"
                                  placeholder="Daging sapi ayam segar"></textarea>
                    </div>

                </div>
                <div class="modal-footer row">
                    <div class=" d-flex flex-row justify-content-end">

                        <div>
                            <button class="btn text-only-outlined cancel-btn" data-bs-dismiss="modal"
                                    wire:click="closeModalNewItem">Batal
                            </button>
                            <button class="btn btn-text-only-primary margin-left-10" data-bs-toggle="modal"
                                    data-bs-target="modalNewItem">Simpan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</x-page-layout>

@section('footer-script')
    <script>
        document.addEventListener('livewire:initialized', () => {
            $("#modalItem").on("shown.bs.modal", function () {
                var modalBody = $(this).find(".modal-body");
                var isRequesting = false;

                modalBody.off("scroll"); // Matikan event scroll sebelum menghubungkan lagi

                // infinity loading
                modalBody.on("scroll", function () {
                    var scrollTop = modalBody.scrollTop();
                    var scrollHeight = modalBody.prop("scrollHeight");
                    var clientHeight = modalBody.prop("clientHeight");

                    // deteksi scroll layar dipaling bawah
                    if (scrollTop + clientHeight + 1 >= scrollHeight && !isRequesting) {
                        isRequesting = true;

                        // trigger event untuk meload data lebih banyak
                    @this.dispatch('load-more');
                    }
                });

            });


            // deteksi modal ditutup
            $("#modalItem").on("hidden.bs.modal", function () {
                // kirim event ke livewire untuk menutup modal
            @this.dispatch('dismiss-modal');
            });


            // deteksi modal item baru ditutup
            $("#modalNewItem").on("hidden.bs.modal", function () {
                // kirim event ke livewire untuk menutup modal
            @this.dispatch('dismiss-modal-new-item');
            });


        @this.on('reject-checkbox', (event) => {
            // tampilkan pesan tidak boleh tambahkan item lebih dari satu area atau rak
            alert('hanya diperbolehkan satu item di satu area / rack, item ini tidak akan ditambahkan');

            // batalkan item yang dicheck
            $('.item-modal .name-item-modal:contains(' + event + ')').each(function () {
                // Melakukan sesuatu dengan elemen yang ditemukan, misalnya, mencetak teksnya
                $(this).closest('.data-modal').find('#itemCheckBox').prop('checked', false);
            });
        });


        });

    </script>
@endsection




