<x-page-layout>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Daftar central kitchen
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    {{--                    <div class="dropdown margin-left-10">--}}
                    {{--                        <button class="btn btn-dropdown-no-icon dropdown-toggle" type="button"--}}
                    {{--                                data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">--}}
                    {{--                            Semua outlet--}}
                    {{--                        </button>--}}
                    {{--                        <ul class="dropdown-menu">--}}

                    {{--                            <li><a class="dropdown-item" href="#">Action</a></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}


                    <a href="/central-kitchen/list-central-kitchen/add-central-kitchen" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10">Tambah central kitchen
                        </button>
                    </a>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>

    <div id="content-loaded">

        <div class="row">
            <div class="col-sm-12">
                <table id="" class="table borderless table-hover">
                    <thead class="table-head-color">
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Nomor telpon</th>
                        <th scope="col">Email</th>
                        <th scope="col">Alamat</th>
                    </tr>
                    </thead>
                    <tbody id="listCentralKitchen">
                    @foreach($centralKitchens as $centralKitchen)
                        <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            wire:click="view('{{ $centralKitchen->id }}')">
                            <td class="code">
                                {{ $centralKitchen->code }}</td>
                            <td>{{ $centralKitchen->name }}</td>
                            <td>{{ $centralKitchen->phone }}</td>
                            <td>{{ $centralKitchen->email }}</td>
                            <td>{{ $centralKitchen->address }}</td>
                        </tr>

                    @endforeach


                    </tbody>
                </table>
                {{ $centralKitchens->links() }}

            </div>
        </div>
    </div>


    <script>

    </script>


</x-page-layout>
