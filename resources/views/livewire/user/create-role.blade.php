@php use App\Models\Permission;use App\Models\PermissionCategory; @endphp
<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Buat role
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">

                    <button type="btn"
                            class="btn btn-text-only-primary btn-nav margin-left-10"
                            wire:click="saveRole"
                    >Simpan
                    </button>

                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>

        <div id="content-loaded">
            <div class="row">
                <div class="col-sm-4 offset-1">
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
                </div>
                <div class="col-sm-5 offset-1">
                    {{-- NAMA ROLE   --}}
                    <label class="form-label input-label margin-top-24">Permission</label>

                    @error('permissions') <span class="text-danger">{{ $message }}</span> @enderror
                    <div id="divider" class="margin-symmetric-vertical-6"></div>

                    <table id="tableAccessPermission"
                           class="table table-container borderless table-hover margin-top-28">
                        <thead class="table-head-color">
                        <tr>
                            <th scope="col">Aktif</th>
                            <th scope="col">Name</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach(PermissionCategory::all() as $category)
                            <tr>
                                <th colspan="2">{{ $category->name }}</th>
                            </tr>
                            @foreach($category->permissions as $permission)
                                <tr wire:key="{{ $permission->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   value="{{ $permission->id }}"
                                                   wire:change="handleSelectedPermission('{{ $permission->name }}')"
                                                   id="flexCheckDefault">
                                        </div>
                                    </td>
                                    <td>{{ $permission->name }}</td>
                                </tr>
                            @endforeach
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>


        </div>

    </x-slot>


</x-page-layout>

@section('footer-script')

@endsection
