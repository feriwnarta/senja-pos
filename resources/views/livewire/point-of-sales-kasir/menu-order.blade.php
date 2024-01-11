<x-page-layout-pos>
    <livewire:components.navbar-kasir.header-pos-kasir>
        <div class="menu-content-wrapper">
            <div class="menu-content">
                @for ($data = 0; $data < 30; $data++) <div class="modal" id="OrderMenuModal" tabindex="-1"
                    aria-labelledby="OrderMenuModalLabel" aria-hidden="true">
                    <livewire:point-of-sales-kasir.menu-order-modal>
            </div>
            <Button type="button" class="menu-card" data-bs-toggle="modal" data-bs-target="#OrderMenuModal">
                <div class="card-body">
                    <img src="../img/chicken.png" alt="Test">
                </div>
                <p class="menu-title">
                    Bubur Hot Pot
                </p>
            </Button>
            @endfor
        </div>
        <livewire:point-of-sales-kasir.menu-order-detail>
</x-page-layout-pos>