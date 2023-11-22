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
                        Tambah kategori item
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-secondary btn-nav margin-left-10"
                                @click="$dispatch('cancel-edit-warehouse')">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            @click="$dispatch('save-category')"
                    >Simpan
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">
        <div class="row">

            {{-- Tamabah kategori baru --}}
            <form>
                <div class="col-sm-4 offset-1">
                    {{-- Kode kategori --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode kategori</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="KTUH01"
                               wire:model.live.debounce.600ms="code">
                        @error('code') <span class="error">{{ $message }}</span> @enderror

                    </div>

                    {{-- Nama kategori --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama kategori</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Unggas"
                               wire:model.live.debounce.600ms="name">
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>


                    {{-- Tambah item ke kategori --}}
                    <div class="d-flex flex-row align-items-center margin-top-24">
                        <div class="body-text-regular">Item</div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="2" height="20" viewBox="0 0 2 20" fill="none"
                             class="margin-left-102">
                            <path d="M0.621094 0V20" stroke="#E0E0E0"/>
                        </svg>

                        <button class="btn icon-text margin-left-16" type="button" id="addItem" data-bs-toggle="modal"
                                data-bs-target="#modalAddCategory"
                                @click="$dispatch('load-item')"
                        >
                            + Tambah item ke kategori
                        </button>
                    </div>
                    @error('selectedItem') <span class="error">{{ $message }}</span> @enderror

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    {{-- LOOPIMG ISI SELECTED ITEM JIKA ISSAVE BERNILAI TRUE --}}

                    @if($isSave)
                        <div class="d-flex flex-wrap margin-top-12">
                            @foreach($selectedItem as $select)
                                <div wire:key="{{ $select['id'] }}"
                                     class="d-flex flex-row align-items-center margin-right-16 margin-bottom-8"
                                >
                                    <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                           wire:click="unSelect('{{ $select['id'] }}')"
                                           checked/>
                                    <div
                                        class="body-text-regular name-item-modal margin-left-8">{{ $select['name'] }}</div>
                                </div>
                            @endforeach
                        </div>

                    @endif


                    <div class="margin-top-24">
                        <label for="dropdownCategory" class="form-label input-label">Unit</label>
                        <div class="dropdown" id="dropdownCategory">
                            <button class="btn dropdown-toggle dropdown-default" type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    wire:click="loadUnit"
                            >
                                {{ (empty($unitSelected)) ? 'Pilih unit ' : $unitSelected }}
                            </button>

                            <ul class="dropdown-menu" style="min-height: 120px" wire:ignore.self>
                                <li>
                                    <input type="text" placeholder="Cari Unit" style="margin:6px; width: 95%;"
                                           wire:model.live.debounce.600ms="search">
                                </li>

                                @if(!empty($units))

                                    @foreach($units as $unit)
                                        <li wire:key="{{ $unit['id'] }}">
                                            <a class="dropdown-item"
                                               href="#"
                                               onclick="closeDropdown()"
                                               wire:click="setUnit('{{ $unit['id'] }}', '{{ $unit['name'] }}')"
                                            >
                                                {{ $unit['name'] }}
                                            </a>
                                        </li>
                                    @endforeach

                                @endif
                                <div wire:loading>
                                    <div class="position-absolute start-50 translate-middle">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </ul>
                        </div>
                        @error('unit') <span class="error">{{ $message }}</span> @enderror
                    </div>


                </div>
            </form>

        </div>
    </div>


    {{-- MODAL TAMBAH ITEM KE KATEGORI --}}
    <div wire:ignore.self class="modal modal-input fade" id="modalAddCategory" tabindex="-1"
         role="dialog"
    >
        <!-- Konten modal -->
        <div class="modal-dialog modal-lg  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                    <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                        Tambah item ke kategori</h1>
                </div>
                <div class="modal-body">


                    {{-- TODO: Buat fungsi pencarian item --}}
                    <form class="d-flex ">
                        <input class="form-control full-search-bar clear container-fluid" type="search"
                               placeholder="Search"
                               aria-label="Search">
                    </form>

                    {{-- LOOPING ISI ITEMS --}}
                    @if(!empty($items))
                        @foreach($items as $item)
                            <div wire:key="{{ $item['id'] }}" class="items-modal">
                                <div class="d-flex align-items-center data-modal">
                                    <div
                                        class="item-modal d-flex flex-row align-items-center justify-content-between">

                                        <div class="d-flex flex-row align-items-center">
                                            <img class="items-ingredient-img"
                                                 src="{{ ($item['item_image'] == null) ? asset('img/no-image.png') : asset("storage/item-image/{$item['item_image']}") }}"
                                                 alt="">

                                            <div
                                                class="body-text-regular name-item-modal">{{ $item['name'] }}</div>
                                        </div>
                                        <div>
                                            <input id="itemCheckBox" class="red-input checkbox" type="checkbox"
                                                   wire:click="selectItem('{{ $item['id'] }}', '{{ $item['name'] }}')"/>
                                        </div>

                                    </div>
                                </div>
                                <div id="divider" class="margin-top-20"></div>
                            </div>
                        @endforeach
                    @endif


                    <div wire:loading>
                        <div class="position-absolute start-50 translate-middle">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer row">
                    <div class=" d-flex flex-row justify-content-end">
                        <div>
                            <button class="btn text-only-outlined cancel-btn" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button class="btn btn-text-only-primary margin-left-10" data-bs-dismiss="modal"
                                    wire:click="saveSelectedItem">Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-page-layout>

@section('footer-script')
    <script src="{{ asset('js/add-category.js') }}"></script>
@endsection
