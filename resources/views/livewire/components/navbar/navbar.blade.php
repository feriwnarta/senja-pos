<div class="navbar-app">
    <div class="content-navbar d-flex flex-row justify-content-between">

        <div id="nav-leading" class="d-flex flex-row align-items-center">
            <div class="navbar-title">
                {{ $title }}
            </div>
        </div>

        <div id="nav-action-button" class="d-flex flex-row align-items-center">

            @if($search == true)
                <form class="d-flex">
                    <input class="form-control search-bar clear" type="search" placeholder="Search" aria-label="Search">
                </form>
            @endif
            
        </div>

    </div>
    <div id="title-divider"></div>
    <div id="divider"></div>
</div>