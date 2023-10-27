<x-page-layout>

    <x-slot name="sidebar">
        <livewire:components.sidebar.sidebar/>
    </x-slot>

    <x-slot name="appBar">
        <div class="navbar-app">
            <div class="content-navbar d-flex flex-row justify-content-between">

                <div id="nav-leading" class="d-flex flex-row align-items-center">
                    <div class="navbar-title">
                        Category
                    </div>
                </div>

                <div id="nav-action-button" class="d-flex flex-row align-items-center">


                    <form class="d-flex">
                        <input class="form-control search-bar clear" type="search" placeholder="Search"
                               aria-label="Search">
                    </form>


                </div>
            </div>
            <div id="title-divider"></div>
            <div id="divider"></div>
        </div>
    </x-slot>


    <livewire:point-of-sales.pos-category-page lazy/>


</x-page-layout>
