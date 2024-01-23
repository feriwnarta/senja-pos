<nav class="navbar sticky-top" style="background-color: #fff;">
    <livewire:components.navbar-kasir.sidebar-kasir>
        <div class="sidebar-logo d-flex align-items-center gap-5">
            <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" role="button"
                aria-controls="offcanvasScrolling">
                <i class="menu-icon"></i>
            </button>
            <img src="{{ asset('img/cahaya_senja_logo.png') }}" alt="logo cahaya senja" class="logo-navbar">
        </div>
        <form class="w-50" role="search">
            <input class="form-control h-32" type="search" placeholder="Pencarian" aria-label="Search">
        </form>
        <div>
            <i class="bell-icon"></i>
        </div>
</nav>