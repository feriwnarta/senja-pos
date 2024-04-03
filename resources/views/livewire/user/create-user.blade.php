<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat User
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <a href="/user/create-user" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                        >Simpan
                        </button>
                    </a>
                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">
            <div class="row">
                <div class="col-sm-4 offset-1">


                    {{-- Nama --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               placeholder="Masukan nama"
                               wire:model.live.debounce.600ms="name">
                        @error('name') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- Email --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Email</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="email" class="form-control input-default"
                               placeholder="Masukan email"
                               wire:model.live.debounce.600ms="email">
                        @error('email') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- Password --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Password</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="password" class="form-control input-default"
                               placeholder="Masukan password"
                               wire:model.live.debounce.600ms="password">
                        @error('password') <span text-xs text-red-600>{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

    </x-slot>


</x-page-layout>

