@php use Illuminate\Support\Facades\Log; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Detail resep
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                    >Update
                    </button>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <div id="content-loaded">

        <div class="row">
            <div class="col-sm-4 offset-1">
                {{-- Kode kategori --}}
                <div class="container-input-default">
                    <label for="warehouseInput"
                           class="form-label input-label">Kode resep</label>
                    <div id="divider" class="margin-symmetric-vertical-6"></div>
                    <input type="name" class="form-control input-default"
                           id="warehouseInput" disabled
                           value="{{ $recipeItem->code }}"
                    >
                </div>

                {{-- PILIH BAHAN --}}
                <div class="margin-top-20">
                    <div class="container-input-default">
                        <label for="description" class="form-label">Bahan</label>
                        <div id="divider" class="margin-symmetric-vertical-6"></div>
                        <select class="form-select input-default"
                                disabled
                        >
                            <option value="" selected>{{ $recipeItem->item->name }}</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="row margin-top-32">
                <div class="col-sm-4 offset-1">
                    {{-- RESEP --}}
                    <div>
                        <label class="form-label input-label">Resep</label>

                        <div id="divider" class="margin-top-8"></div>
                        <div class="margin-top-8">
                            <table class="table table-bordered only-underline table-create-recipes body-text-regular">
                                <thead>
                                <tr class="no-border-side">
                                    <th scope="col">Bahan</th>
                                    <th scope="col">Penggunaan</th>
                                    <th scope="col">Satuan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($recipeItem))
                                    @foreach($recipeItem->recipeDetail as $recipe)
                                        <tr>
                                            <td>{{ $recipe->item->name }}</td>
                                            <td>{{ $recipe->usage }}</td>
                                            <td>{{ $recipe->item->unit->name }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-page-layout>


