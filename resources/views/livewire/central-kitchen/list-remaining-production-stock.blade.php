<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Stok sisa produksi
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>

                    <div class="dropdown margin-left-16">
                        <select
                            aria-label="select-central-kitchen"
                            class="form-select input-default"
                            id="resupplyOutlet">
                            @if(isset($centralKitchens))
                                @foreach($centralKitchens as $kitchen)
                                    <option wire:model="selectCentralKitchen"
                                            value="{{ $kitchen->id }}">{{ $kitchen->name }}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">
            <table id="" class="table borderless table-hover margin-top-28">
                <thead class="table-head-color">
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Unit</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($stocks))
                    @foreach($stocks as $stock)
                        <tr wire:key="{{ $loop->iteration }}">
                            <td>{{ $stock->item->name }}</td>
                            <td>{{ $stock->qty_on_hand}}</td>
                            <td>{{ $stock->item->unit->name}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
