<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Pemasok
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <a href="/supplier/create-supplier" wire:navigate>
                        <button type="btn"
                                wire:loading.attr="disabled"
                                class="btn btn-text-only-primary btn-nav margin-left-10">Buat supplier
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
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Nomor telpon</th>
                        <th scope="col">Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($suppliers as $supplier)
                        <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            wire:click="">
                            <td>{{ $supplier->name }}</td>
                            <td>{{ ($supplier->address == '' || $supplier->address == null) ? 'Tanpa alamat' : $supplier->address }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>


                {{ $suppliers->links() }}


            </div>
        </div>


    </div>
</x-page-layout>
