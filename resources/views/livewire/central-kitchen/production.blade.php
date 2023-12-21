@php use Illuminate\Support\Carbon;use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Produksi
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search"
                               placeholder="{{ __('app_locale.input.cari') }}"
                               aria-label="Search" wire:model.live.debounce.600ms="search">
                    </form>


                    <div class="dropdown margin-left-10">
                        <select class="form-select input-default" id="resupplyOutlet"
                                wire:model="selected" wire:change="centralKitchenChange">
                            <option value="all" disabled selected>Semua central kitchen</option>

                            @if(!empty($centralKitchens))
                                @foreach($centralKitchens as $centralKitchen)
                                    <option value="{{ $centralKitchen->id }}">{{ $centralKitchen->name }}</option>
                                @endforeach
                            @endif


                        </select>
                    </div>

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
                        <th scope="col">Kode referensi</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Catatan</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody id="listPurchaseOrder">
                    @foreach($requestStock as $request)
                        <tr class="items-table-head-color" id="po1" style="cursor: pointer"
                            wire:click="detailProduction('{{ $request->id }}')">
                            <td class="code">{{ $request->code }}</td>
                            <td> {{ Carbon::createFromFormat('Y-m-d H:i:s', $request->created_at)->locale('id_ID')->isoFormat('D MMMM Y') }}</td>
                            <td>{{ ($request->note == null) ? 'tanpa catatan' : $request->note }}</td>
                            <td>{{ optional($request->requestStockHistory->last())->status }}</td>

                        </tr>
                    @endforeach


                    </tbody>
                </table>
                {{ $requestStock->links() }}

            </div>
        </div>
    </div>


    <script>

    </script>


</x-page-layout>
