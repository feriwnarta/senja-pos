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
                        {{ ($mode == 'view') ? __('app_locale.text.detailGudang') : 'Edit gudang'}}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    @if($mode == 'view')
                        <div class="dropdown margin-left-10">
                            <button type="btn"
                                    class="btn btn-text-only-secondary btn-nav margin-left-10">{{ __('app_locale.button.hapus') }}</button>
                        </div>


                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                @click="$dispatch('edit-warehouse')"
                        >{{ __('app_locale.button.edit') }}</button>

                    @else
                        <div class="dropdown margin-left-10">
                            <button type="btn"
                                    class="btn btn-text-only-secondary btn-nav margin-left-10">Batal
                            </button>
                        </div>


                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                
                        >Simpan
                        </button>
                    @endif


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <livewire:warehouse.detail-warehouse-page lazy/>


</x-page-layout>

@section('footer-script')
    <script data-navigate-once src="{{ asset('js/detail-warehouse.js') }}"></script>
@endsection
