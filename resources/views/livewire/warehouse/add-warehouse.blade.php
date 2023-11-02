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
                            wire:click.prevent="saveWarehouse">{{ __('app_locale.button.simpan') }}</button>

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
                                        {{ \Illuminate\Support\Facades\Log::info($value) }}
                                        <tr>
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


                                    <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                           wire:click="addItem('{{ $data['id'] }}', '{{ $data['name'] }}')"/>


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
                                    data-bs-target="#exampleModal">Buat item baru
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="modal fade modal-input" data-bs-backdrop="static" id="exampleModal" tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                    <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                        Buat item baru</h1>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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




