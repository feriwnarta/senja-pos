<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Daftar Unit
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <a href="/composition/unit/add-unit" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                                @click="$dispatch('saveEditWarehouse')"
                        >Tambah Unit
                        </button>
                    </a>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">


            <table id="" class="table borderless table-hover margin-top-28">
                <thead class="table-head-color">
                <tr>
                    <th scope="col">Kode</th>
                    <th scope="col">Unit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($units as $unit)
                    <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                        wire:click="view('{{ $unit->id }}')"
                    >
                        <td>{{ $unit->code }}</td>
                        <td>{{ $unit->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if(isset($units))
                {{ $units->links() }}
            @endif
        </div>

    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
