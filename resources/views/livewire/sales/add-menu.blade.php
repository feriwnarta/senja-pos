<x-page-layout>
    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat menu
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button href="/sales/list-menu" wire:navigate type="btn"
                        class="btn btn-text-only-danger btn-nav margin-left-10">
                        Batal
                    </button>

                    <button type="btn" wire:loading.attr="disabled"
                        class="btn btn-text-only-primary btn-nav margin-left-10" wire:click="save">Simpan
                    </button>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">
            <div class="row">
                <div class="col-sm-6 offset-1">
                    <div class="d-flex flex-column gap-4">

                        {{-- KODE MENU --}}
                        <div class="container-input-default">

                            <label for="menuCode" class="form-label input-label">Kode Menu</label>

                            <input type="name" class="form-control input-default" id="menuCode"
                                placeholder="CHMSPC01" wire:model.blur="code">

                            @error('code')
                                <small class="d-block text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        {{-- KATEGORI ITEM --}}
                        <div class="container-input-default">

                            <label for="categoryItem" class="form-label">Kategori item</label>

                            <select id="categoryItem" class="form-select input-default" wire:model.blur="code_category">

                                <option value="" hidden>Pilih kategori item</option>
                                {{-- @if (isset($allCategoryMenus)) --}}
                                @foreach ($allCategoryMenus as $category)
                                    <option value="{{ $category->code }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                {{-- @endif --}}

                            </select>

                            @error('code_category')
                                <small class="d-block text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        {{-- NAMA MENU --}}
                        <div class="container-input-default">

                            <label for="menuName" class="form-label input-label">Nama Menu</label>

                            <input type="name" class="form-control input-default" id="menuName"
                                placeholder="Chamie Special XO" wire:model.blur="name">

                            @error('name')
                                <small class="d-block text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="container-input-default">

                            <label for="menuDescription" class="form-label input-label">Deskripsi Menu</label>

                            <textarea type="name" class="form-control textarea" id="menuDescription" rows="5"
                                placeholder="Chamie / mie goreng special dengan saus XO" wire:model.blur="description"></textarea>

                            {{-- @error('description')
                                <small class="d-block text-danger">{{ $message }}</small>
                            @enderror --}}

                        </div>


                        <div class="container-input-default">
                            <div class="row">

                                {{-- PRICE --}}
                                <div class="col-sm-7">

                                    <label for="pricing" class="form-label input-label">Pricing</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <input type="name" class="form-control input-default" id="pricing"
                                        placeholder="65.000" wire:model.blur="price">

                                    @error('price')
                                        <small class="d-block text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                                {{-- CODE SKU --}}
                                <div class="col-sm-5">

                                    <label for="codeSKU" class="form-label input-label">Code SKU</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <input type="name" class="form-control input-default" id="codeSKU"
                                        placeholder="CHB01" wire:model.blur="sku">

                                    @error('sku')
                                        <small class="d-block text-danger">{{ $message }}</small>
                                    @enderror

                                </div>

                            </div>

                            {{-- ADD VARIANT MODAL --}}
                            <button class="btn btn-text-only-primary w-100 mt-2" data-bs-toggle="modal"
                                data-bs-target="#modalAddVariant">Add Variant</button>
                            <livewire:sales.modal-add-variant>

                                {{-- @if (!empty($submittedVariant))

                                    <table id="" class="table borderless table-hover my-3">
                                        <thead class="table-head-color">
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">SKU</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listCentralKitchen">
                                            @foreach ($submittedVariant as $data)
                                                <tr class="items-table-head-color" id="po1" wire:click="">
                                                    <td class="align-middle">{{ $data['variantName'] }}</td>
                                                    <td class="align-middle">{{ $data['variantPrice'] }}</td>
                                                    <td class="align-middle">{{ $data['variantSKU'] }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                @endif --}}

                                <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                        {{-- INVENTORY SETTING MODAL --}}
                        <div class="container-input-default">

                            <label class="form-label input-label">Inventory</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <button class="btn btn-text-only-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#modalSettingInventory">Setting Inventory</button>
                            <livewire:sales.modal-setting-inventory>

                                <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                        {{-- COGS SETTING MODAL --}}
                        <div class="container-input-default">

                            <label class="form-label input-label">COGS</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <button class="btn btn-text-only-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#modalSettingCogs">Setting COGS</button>
                            <livewire:sales.modal-setting-cogs>

                                <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                    </div>

                </div>

                {{-- MENU IMAGE UPLOAD --}}
                <div class="col-sm-3 offset-2">

                    @if ($thumbnail && !$errors->has('thumbnail'))
                        <img src="{{ $thumbnail->temporaryUrl() }}" alt="Menu image" width="220" height="220"
                            @click="document.getElementById('fileInput').click()">
                    @else
                        <img src="{{ asset('img/no-image.png') }}" alt="No image" width="140" height="140"
                            @click="document.getElementById('fileInput').click()">
                    @endif

                    <div wire:loading wire:target="thumbnail">Uploading...</div>

                    @error('thumbnail')
                        <small class="d-block text-danger">{{ $message }}</small>
                    @enderror

                    <input type="file" id="fileInput" style="display: none;" accept="image/*"
                        wire:model="thumbnail">

                </div>
            </div>
        </div>
    </x-slot>
</x-page-layout>
