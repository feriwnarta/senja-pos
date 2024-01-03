@php use Illuminate\Support\Facades\Storage; @endphp
<x-page-layout>


    <x-slot name="appBar">

        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Item
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex margin-left-10">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default {{ ($notSelected) ? 'border border-danger' : '' }}"
                                id="resupplyOutlet" wire:model.live="selected" wire:change="">
                            <option value="" disabled selected>Semua outlet & central kitchen</option>
                            @foreach($outletCentralKitchenDropdown as $result)
                                <option value="{{ $result->id }}">{{ $result->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <button type="btn"
                            wire:click="addItem"
                            class="btn btn-text-only-primary btn-nav margin-left-10">+ Buat
                        item
                    </button>


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
                        <th scope="col">Unit</th>
                        <th scope="col">Deskripsi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            wire:click="">
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            {{--                            <td>--}}
                            {{--                                <img width="70" height="70" src="{{ Storage::url("{$item->thumbnail}") }}" alt=""--}}
                            {{--                                     srcset="{{ Storage::url("{$item->thumbnail}") }} 70w, {{ Storage::url("{$item->thumbnail}") }} 100w, {{ Storage::url("{$item->thumbnail}") }} 150w"--}}
                            {{--                                     class="img-thumbnail">--}}
                            {{--                            </td>--}}
                            <td>{{ $item->unit->name }}</td>
                            <td>{{ ($item->description == '' || $item->description == null) ? 'Tanpa deskripsi' : $item->description }}</td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>


            </div>


        </div>
    </div>


</x-page-layout>
