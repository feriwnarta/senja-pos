<x-page-layout>
    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat kategori menu
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button href="/sales/list-category" wire:navigate type="btn"
                        class="btn btn-text-only-danger btn-nav margin-left-10">
                        Batal
                    </button>

                    <button type="btn" class="btn btn-text-only-primary btn-nav margin-left-10"
                        wire:click="saveCategoryMenu">
                        Simpan
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

                            <label for="code" class="form-label input-label">Kode kategori</label>

                            <input type="name" class="form-control input-default" id="code" name="code"
                                placeholder="CHMSPC01" wire:model.blur="code">

                            @error('code')
                                <small class="d-block text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="container-input-default">

                            <label for="name" class="form-label input-label">Nama kategori</label>

                            <input type="name" class="form-control input-default" id="name" name="name"
                                placeholder="Mie" wire:model.blur="name">

                            @error('name')
                                <small class="d-block text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </x-slot>
</x-page-layout>
