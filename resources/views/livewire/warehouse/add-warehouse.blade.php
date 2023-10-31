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
                            class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.simpan') }}</button>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded" wire:ignore>
        <div class="row">
            <div class="col-sm-6 offset-1">
                <div class="container-input-default">

                    <label for="warehouseInput"
                           class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>


                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plKodeGudang') }}">

                    {{-- <div class="invalid-feedback">
                        Invalid Feedback: Nama harus diisi.
                    </div> --}}

                    {{-- <div id="{{ $id }}" class="{{ $class }}-error input-error">--}}
                </div>


                <div class="container-input-default margin-top-24">

                    <label for="warehouseInput"
                           class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>


                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plNamaGudang') }}">

                    {{-- <div class="invalid-feedback">
                        Invalid Feedback: Nama harus diisi.
                    </div> --}}

                    {{-- <div id="{{ $id }}" class="{{ $class }}-error input-error">--}}
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


                        <tr id="addWarehouseAction">
                            <td>
                                <button class="btn icon-text caption-medium" type="button" id="addArea"
                                >
                                    + Area
                                </button>
                            </td>
                            <td>

                                <button class="btn icon-text caption-medium" type="button" id="addRack"
                                >
                                    + Rak
                                </button>

                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>


    {{-- Modal Item --}}
    <div class="modal-input modal fade" id="modalItem" tabindex="-1" aria-labelledby="modalItem" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                    <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                        {{ __('app_locale.modal.modalTambahGudang') }}</h1>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

</x-page-layout>

@section('footer-script')
    <script src="{{ asset("js/add-warehouse.js") }}"></script>
@endsection



