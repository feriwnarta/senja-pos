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
                        {{ $mode == 'view' ? 'Detail unit' : 'Edit unit'  }}
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
                                @click="$dispatch('edit-unit')"
                        >{{ __('app_locale.button.edit') }}</button>

                    @else
                        <div class="dropdown margin-left-10">
                            <button type="btn"
                                    class="btn btn-text-only-secondary btn-nav margin-left-10"
                                    @click="$dispatch('cancel-edit')">
                                Batal
                            </button>
                        </div>


                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                @click="$dispatch('save-edit')"
                        >Simpan
                        </button>
                    @endif

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>


        <livewire:warehouse.detail-unit-page lazy/>
    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
