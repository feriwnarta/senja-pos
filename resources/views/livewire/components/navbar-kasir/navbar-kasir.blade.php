<div class="navbar-app">
  <livewire:components.navbar-kasir.sidebar-kasir>
    <nav class="navbar px-5" style="background-color: #fff;">
        <div class="sidebar-logo d-flex align-items-center gap-5">
            <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" role="button" aria-controls="offcanvasScrolling">
                <img src="{{ asset('img/icons/menu.png') }}" class="" alt="Notification Bell">
              </button>
            <img src="{{ asset('img/cahaya_senja_logo.png') }}" alt="logo cahaya senja" class="logo-navbar">
        </div>
        <form class="d-flex w-50" role="search">
            <input class="form-control" type="search" placeholder="Pencarian" aria-label="Search">
        </form>
        <div>
            <img src="{{ asset('img/icons/bell.png') }}" alt="Notification Bell" class="bell-navbar">
        </div>
    </nav>
    <header>
        <livewire:components.navbar-kasir.header-pos-kasir>
    </header>
</div>