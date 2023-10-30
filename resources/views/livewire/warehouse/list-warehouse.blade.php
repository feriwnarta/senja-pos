<x-page-layout>

    <x-slot name="sidebar">
        <livewire:components.sidebar.sidebar/>
    </x-slot>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        {{ __('sidebar_locale.gudang.daftarGudang') }}
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search" placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search">
                    </form>

                    <div class="dropdown margin-left-10">
                        <button class="btn btn-dropdown-no-icon dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            {{ __('app_locale.button.outlet') }}
                        </button>
                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item" href="#">Action</a></li>
                        </ul>
                    </div>


                    <a href="/warehouse/list-warehouse/add-warehouse" wire:navigate>
                        <button type="btn" class="btn btn-text-only-primary btn-nav margin-left-10">{{ __('app_locale.button.tambahGudang') }}</button>
                    </a>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">

    {{-- Table daftar gudang --}}
        <table id="tableMenu" class="table table-hover">
            <thead class="text-center">
            <tr>
                <th>Kode gudang</th>
                <th>Nama gudang</th>
                <th>Area</th>
                <th>Rak</th>
                <th>Alamat</th>
                <th>Penanggung jawab</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>GPST01</td>
                <td>Gudang pusat</td>
                <td>Area A</td>
                <td>A1,A2, A3</td>
                <td>Jl Satu dua tiga raya</td>
                <td>Joko</td>
            </tr>
            </tbody>
        </table>

    </div>



    <script>
        $("#tableMenu").DataTable({
            paging: true,
            selected: false,
            lengthChange: false,
            searching: false,
            info: false,
        });
    </script>


</x-page-layout>
