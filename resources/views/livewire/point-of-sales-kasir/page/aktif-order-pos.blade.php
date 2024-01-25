<x-page-layout-pos>
    <div class="active-order-wrapper">
        <div class="header-active-order">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <table class="table table-borderless">
            <thead class="table-head">
                <tr>
                    <th class="text-light-14 color-4040" scope="col">Meja</th>
                    <th class="text-light-14 color-4040" scope="col">Nama</th>
                    <th class="text-light-14 color-4040" scope="col">Tipe</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 5; $i++)
                    <tr id="sidebar-pos-trigger" class="table-row-bordered">
                        <td class="text-light-14 color-6161">T17</td>
                        <td class="text-light-14 color-6161">William</td>
                        <td class="text-light-14 color-6161">Dine in</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div>
            <button class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36" data-bs-toggle="modal"
                data-bs-target="#modalOtorisasiPin">Pin</button>
            <button class="btn btn-primary">Cetak Pesanan</button>
        </div>
    </div>
    <livewire:point-of-sales-kasir.modal-pin>
        <livewire:point-of-sales-kasir.side-menu-order>
</x-page-layout-pos>
