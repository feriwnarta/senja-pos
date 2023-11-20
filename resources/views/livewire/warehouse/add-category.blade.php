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
                            @click="$dispatch('saveEditWarehouse')"
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
                               wire:model.live.debounce.600ms="codeWarehouse">
                        @error('codeWarehouse') <span class="error">{{ $message }}</span> @enderror

                    </div>

                    {{-- Nama kategori --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama kategori</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Unggas"
                               wire:model.live.debounce.600ms="codeWarehouse">
                        @error('codeWarehouse') <span class="error">{{ $message }}</span> @enderror
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

                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                </div>
            </form>

        </div>
    </div>


    {{-- MODAL EDIT ITEM --}}
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


</x-page-layout>

@section('footer-script')

@endsection
