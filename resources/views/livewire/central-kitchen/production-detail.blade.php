@php use Carbon\Carbon; @endphp
<x-page-layout>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail produksi
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <div class="dropdown margin-left-10">
                        <button type="btn"
                                class="btn btn-text-only-danger btn-nav margin-left-10"
                                @click="$dispatch('cancel-edit-warehouse')">
                            Batal
                        </button>
                    </div>


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="save"
                    >Terima dan lanjutkan
                    </button>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">

        <div class="row">
            <div class="col-sm-5 offset-1">
                {{-- KODE REFERENSI --}}
                <div>
                    <p class="subtitle-3-regular">Kode referensi</p>
                    <div id="divider" class="margin-top-6"></div>
                    <p class="margin-top-6 subtitle-3-medium">{{ $requestStock->code }}</p>
                </div>


                <div class="margin-top-24">
                    <p class="subtitle-3-regular">Tanggal</p>
                    <div id="divider" class="margin-top-6"></div>
                    <p class="margin-top-6 subtitle-3-medium">
                        {{ Carbon::createFromFormat('Y-m-d H:i:s', $requestStock->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}
                    </p>

                </div>

                
            </div>
        </div>
    </div>


    <script>

    </script>


</x-page-layout>
