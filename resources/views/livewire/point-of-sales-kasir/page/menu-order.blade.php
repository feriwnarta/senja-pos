<x-page-layout-pos>
    <livewire:components.navbar-kasir.header-pos-kasir>
        <div class="menu-content-wrapper">
            <div class="menu-content">
                @foreach ($menuCS as $data)
                    <div class="modal fade" id="modalOrderMenu{{$data['id']}}" tabindex="-1" aria-labelledby="modalOrderMenuLabel"
                        aria-hidden="true" data-bs-backdrop="true" wire:key='{{ $data['id'] }}'>
                        <livewire:point-of-sales-kasir.modal-menu-order>
                    </div>
                    <Button type="button" class="menu-card" data-bs-toggle="modal" data-bs-target="#modalOrderMenu{{$data['id']}}">
                        <div class="card-body">
                            <img src="{{ asset($data['image_url']) }}" alt="Test">
                        </div>
                        <p class="menu-title text-light-12 color-4040 d-block text-truncate text-start">
                            {{ $data['menu_name'] }}
                        </p>
                    </Button>
                    <livewire:point-of-sales-kasir.side-menu-order>
                @endforeach
                <div class="modal fade" id="modalPilihPelanggan" tabindex="-1"
                    aria-labelledby="modalPilihPelangganLabel" aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pilih-pelanggan>
                </div>
                <div class="modal fade" id="modalPelangganBaru" tabindex="-1" aria-labelledby="modalPelangganBaruLabel"
                    aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pelanggan-baru>
                </div>
                <div class="modal fade" id="modalPilihMeja" tabindex="-1" aria-labelledby="modalPilihMejaLabel"
                    aria-hidden="true" data-bs-backdrop="true">
                    <livewire:point-of-sales-kasir.modal-pilih-meja>
                </div>
            </div>
</x-page-layout-pos>
