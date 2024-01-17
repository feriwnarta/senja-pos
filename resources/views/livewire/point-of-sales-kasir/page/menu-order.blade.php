<x-page-layout-pos>
    <livewire:components.navbar-kasir.header-pos-kasir>
        <div class="menu-content-wrapper">
            <div class="menu-content">
                @for ($data = 0; $data < 24; $data++)
                    <div class="modal" id="modalOrderMenu" tabindex="-1" aria-labelledby="modalOrderMenuLabel"
                        aria-hidden="true" data-bs-backdrop="true">
                        <livewire:point-of-sales-kasir.modal-menu-order>
                    </div>
                    <Button type="button" class="menu-card" data-bs-toggle="modal" data-bs-target="#modalOrderMenu">
                        <div class="card-body">
                            <img src="../img/chicken.png" alt="Test">
                        </div>
                        <p class="menu-title">
                            Bubur Hot Pot
                        </p>
                    </Button>
                    <livewire:point-of-sales-kasir.side-menu-order>
                @endfor
                <div class="modal" id="modalPilihPelanggan" tabindex="-1" aria-labelledby="modalPilihPelangganLabel"
                    aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pilih-pelanggan>
                </div>
                <div class="modal" id="modalPelangganBaru" tabindex="-1" aria-labelledby="modalPelangganBaruLabel"
                    aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pelanggan-baru>
                </div>
                <div class="modal" id="modalPilihMeja" tabindex="-1" aria-labelledby="modalPilihMejaLabel"
                    aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pilih-meja>
                </div>
            </div>
</x-page-layout-pos>
