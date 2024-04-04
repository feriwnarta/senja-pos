<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail kategori
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                    >Update
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
                               value="{{ $category->code }}"
                               disabled
                        >

                    </div>

                    {{-- Nama kategori --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama kategori</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Raw material"
                               value="{{ $category->name }}"
                               disabled
                        >
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

