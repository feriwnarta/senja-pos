@php use App\Models\PermissionCategory; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Permission
                    </div>
                </div>

            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">
            <div class="row">
                <div class="col-sm-4">
                    <div
                        class="d-flex flex-column justify-content-end align-items-end position-fixed position-sticky">
                        <button type="btn" class="btn btn-text-only-primary btn-nav" data-bs-toggle="modal"
                                data-bs-target="#modalCreateRole">
                            Buat Role
                        </button>

                        <table id="tableRoles"
                               class="table table-container borderless table-hover margin-top-28">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Role</th>
                                <th scope="col">Guard</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(isset($roles) && !empty($roles))
                                @foreach($roles as $role)
                                    <tr wire:key="{{ $role->id }}">
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                        
                    </div>
                </div>

                <div class="col-sm-4">
                    <div
                        class="d-flex flex-column justify-content-end align-items-end position-fixed position-sticky">
                        <button type="btn" class="btn btn-text-only-primary btn-nav" data-bs-toggle="modal"
                                data-bs-target="#modalCreatePermission"
                        >
                            Buat Permission
                        </button>

                        <table id="tablePermissions"
                               class="table table-container borderless table-hover margin-top-28">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Kategori</th>
                                <th scope="col">Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($permissions) && !empty($permissions))
                                @foreach($permissions as $permission)
                                    <tr wire:key="{{ $permission->id }}">
                                        <td>{{ $permission->category->name }}</td>
                                        <td>{{ $permission->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div
                        class="d-flex flex-column justify-content-end align-items-end position-fixed position-sticky">
                        <button type="btn" class="btn btn-text-only-primary btn-nav" data-bs-toggle="modal"
                                data-bs-target="#modalCreatePermissionCategory"
                        >
                            Buat Permission Category
                        </button>

                        <table id="tablePermissions"
                               class="table table-container borderless table-hover margin-top-28">
                            <thead class="table-head-color">
                            <tr>
                                <th scope="col">Kategori</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($permissionCategories) && !empty($permissionCategories))
                                @foreach($permissionCategories as $category)
                                    <tr wire:key="{{ $category->id }}">
                                        <td>{{ $category->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>


        {{-- MODAL CREATE ROLE --}}
        <div class="modal fade" id="modalCreateRole" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true"
             wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                        <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                            Buat Role</h1>
                    </div>
                    <div class="modal-body">
                        {{-- NAMA ROLE   --}}
                        <div class="container-input-default">
                            <label for="warehouseInput"
                                   class="form-label input-label">Nama</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="roleName" placeholder="Masukan nama role"
                                   wire:model.live.debounce.500ms="roleName">
                            @error('roleName') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                        <div class="modal-footer">
                            <div class=" d-flex flex-row justify-content-end">
                                <div>
                                    <button class="btn btn-text-only-danger margin-left-10" data-bs-dismiss="modal"
                                            aria-label="Close">Tutup
                                    </button>
                                    <button class="btn btn-text-only-primary margin-left-10"
                                            wire:click="saveNewRole">Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- MODAL CREATE PERMISSION --}}
        <div class="modal fade" id="modalCreatePermission" tabindex="-1"
             aria-hidden="true"
             wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                        <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                            Buat Permission</h1>
                    </div>
                    <div class="modal-body">

                        <div class="container-input-default">
                            <label for="description" class="form-label">Kategori</label>
                            <div id="divider" class="margin-symmetric-vertical-6"></div>
                            <select class="form-select input-default" wire:model.live="categoryPermission">
                                <option value="" selected disabled>Pilih</option>
                                @foreach(PermissionCategory::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryPermission') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- NAMA ROLE   --}}
                        <div class="container-input-default margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Nama</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="roleName" placeholder="Masukan nama permission"
                                   wire:model.live.debounce.500ms="permissionName">
                            @error('permissionName') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                        <div class="modal-footer">
                            <div class=" d-flex flex-row justify-content-end">
                                <div>
                                    <button class="btn btn-text-only-danger margin-left-10" data-bs-dismiss="modal"
                                            aria-label="Close">Tutup
                                    </button>
                                    <button class="btn btn-text-only-primary margin-left-10"
                                            wire:click="saveNewPermission">Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- MODAL CREATE PERMISSION --}}
        <div class="modal fade" id="modalCreatePermissionCategory" tabindex="-1"
             aria-hidden="true"
             wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex flex-row justify-content-center bg-primary-main">
                        <h1 class="modal-title modal-input-title" id="exampleModalLabel">
                            Buat Permission Category</h1>
                    </div>
                    <div class="modal-body">

                        {{-- NAMA KATEGORI   --}}
                        <div class="container-input-default margin-top-24">
                            <label for="warehouseInput"
                                   class="form-label input-label">Nama</label>

                            <div id="divider" class="margin-symmetric-vertical-6"></div>

                            <input type="name" class="form-control input-default"
                                   id="roleName" placeholder="Masukan nama kategori"
                                   wire:model.live.debounce.500ms="permissionCategoryName">
                            @error('permissionCategoryName') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                        <div class="modal-footer">
                            <div class=" d-flex flex-row justify-content-end">
                                <div>
                                    <button class="btn btn-text-only-danger margin-left-10" data-bs-dismiss="modal"
                                            aria-label="Close">Tutup
                                    </button>
                                    <button class="btn btn-text-only-primary margin-left-10"
                                            wire:click="saveNewPermissionCategory">Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
