<x-page-layout>
    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ __('app_locale.text.detailGudang') }}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                        {{-- TODO: Buat penghapusan warehouse --}}
                        <div class="dropdown margin-left-10">
                            <button type="btn"
                                    class="btn btn-text-only-secondary btn-nav margin-left-10">{{ __('app_locale.button.hapus') }}</button>
                        </div>


                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                @click="$dispatch('edit-warehouse')"
                        >{{ __('app_locale.button.edit') }}</button>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>

            <div id="content-loaded">
                <div class="row">
                    <div class="col-sm-6 offset-1">
                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">{{ __('app_locale.text.kodeGudang') }}</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plKodeGudang') }}"
                                   disabled
                                   value="{{ $warehouse->warehouse_code }}"
                                   >
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
                                    @foreach($warehouse->areas as $area)
                                        @foreach($area->racks as $key => $rack)
                                                <tr>
                                                    <td>{{ ($key == 0) ? $area->name : '' }}</td>
                                                    <td>{{ $rack->name }}</td>
                                                    <td></td>
                                                </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>

                            </table>
                        </div>


                        <div class="container-input-default  margin-top-16">
                            <label for="warehouseInput"
                                   class="form-label input-label">{{ __('app_locale.text.namaGudang') }}</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="warehouseInput" placeholder="{{ __('app_locale.placeholder.plNamaGudang') }}"
                                   disabled
                                   value="{{ $warehouse->name }}">

                        </div>


                        <div class="margin-top-16">
                            <label for="addressWarehouse" class="form-label">Alamat</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <textarea class="form-control textarea" id="addressWarehouse" rows="5" disabled>{{ $warehouse->address }}</textarea>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-slot>

</x-page-layout>

@section('footer-script')
    <script data-navigate-once src="{{ asset('js/detail-warehouse.js') }}"></script>
@endsection
