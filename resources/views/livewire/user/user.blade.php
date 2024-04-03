<x-page-layout>


    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        User
                    </div>
                </div>


                <div id="nav-action-button" class="d-flex flex-row align-items-center">
                    <a href="/user/create-user" wire:navigate>
                        <button type="btn"
                                class="btn btn-text-only-primary btn-nav margin-left-10"
                        >Buat user
                        </button>
                    </a>
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
                    <th scope="col">Email</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($users) && !empty($users))
                    @foreach($users as $user)
                        <tr class="items-table-head-color" style="cursor: pointer" wire:key="{{ $user->id }}">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
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
