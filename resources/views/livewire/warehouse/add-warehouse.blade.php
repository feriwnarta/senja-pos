<x-page-layout>

    <x-slot name="sidebar">
        <livewire:components.sidebar.sidebar/>
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



                    <button type="btn" class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.simpan') }}</button>



                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">
        <div class="row">
            <div class="col-sm-6 offset-1">
                <div class="container-input-default">

                    <label for="warehouseInput" class="form-label input-label">Kode gudang</label>


                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="BMDGUI01">

                    {{-- <div class="invalid-feedback">
                        Invalid Feedback: Nama harus diisi.
                    </div> --}}

                    {{-- <div id="{{ $id }}" class="{{ $class }}-error input-error">--}}
                </div>


                <div class="container-input-default margin-top-24">

                    <label for="warehouseInput" class="form-label input-label">Nama Gudang</label>


                    <input type="name" class="form-control input-default"
                           id="warehouseInput" placeholder="Gudang pusat">

                    {{-- <div class="invalid-feedback">
                        Invalid Feedback: Nama harus diisi.
                    </div> --}}

                    {{-- <div id="{{ $id }}" class="{{ $class }}-error input-error">--}}
                </div>


                <div class="container-input-default margin-top-24">

                    <label for="warehouseInput" class="form-label input-label">Area gudang</label>

                    <table id="areaGudangTable" class="table-component table table-hover">
                        <thead>
                            <tr>
                                <th>Area</th>
                                <th>Rak</th>
                                <th>Kategori Inventory</th>
                                <th>Item</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="input-no-border make-input caption-medium" placeholder="Area A" style="width: 100%">
                                </td>
                                <td><input type="text" class="input-no-border make-input caption-medium" placeholder="A1" style="width: 100%"></td>
                                <td><input type="text" class="input-no-border make-input caption-medium" placeholder="Bahan mentah" style="width: 100%"></td>
                                <td>
                                    <button class="btn icon-text" type="button" id="addIngredients" style="width: 100%">
                                        +Item
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <button class="btn icon-text caption-medium" type="button" id="addIngredients">
                                        + Area
                                    </button>
                                </td>
                                <td>
                                    <button class="btn icon-text caption-medium" type="button" id="addIngredients">
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
    </div>

</x-page-layout>
