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

                    <button type="btn" wire:click="addWarehouse" wire:loading.attr="disabled"
                        class="btn btn-text-only-primary btn-nav margin-left-10">Simpan
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

                        <div class="container-input-default">

                            <label for="menuCode" class="form-label input-label">Kode Menu</label>

                            <input type="name" class="form-control input-default" id="menuCode"
                                placeholder="CHMSPC01">

                        </div>

                        <div class="container-input-default">

                            <label for="menuName" class="form-label input-label">Nama Menu</label>

                            <input type="name" class="form-control input-default" id="menuName"
                                placeholder="Chamie Special XO">

                        </div>

                        <div class="container-input-default">

                            <label for="menuDescription" class="form-label input-label">Deskripsi Menu</label>

                            <textarea type="name" class="form-control textarea" id="menuDescription" rows="5"
                                placeholder="Chamie / mie goreng special dengan saus XO"></textarea>

                        </div>

                        <div class="container-input-default">
                            <div class="row">

                                <div class="col-sm-7">

                                    <label for="pricing" class="form-label input-label">Pricing</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <input type="name" class="form-control input-default" id="pricing"
                                        placeholder="65.000">

                                </div>

                                <div class="col-sm-5">

                                    <label for="codeSKU" class="form-label input-label">Code SKU</label>

                                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                                    <input type="name" class="form-control input-default" id="codeSKU"
                                        placeholder="CHB01">

                                </div>

                            </div>

                            <button class="btn btn-text-only-primary w-100 mt-2">Add Variant</button>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                        <div class="container-input-default">

                            <label for="pricing" class="form-label input-label">Inventory</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <button class="btn btn-text-only-primary w-100">Setting Inventory</button>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                        <div class="container-input-default">

                            <label for="pricing" class="form-label input-label">COGS</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <button class="btn btn-text-only-primary w-100">Setting COGS</button>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                        </div>

                    </div>

                </div>
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
