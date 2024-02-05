<x-page-layout-pos>
    <div class="active-order-wrapper">
        <div class="header-active-order sidemenu-open">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <div class="active-order-table">
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
                        <tr class="table-row-bordered" data-bs-target="#sidebar-trigger" data-bs-toggle="offcanvas">
                            <td class="text-light-14 color-6161">T17</td>
                            <td class="text-light-14 color-6161">William</td>
                            <td class="text-light-14 color-6161">Dine in</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    {{-- <livewire:point-of-sales-kasir.side-menu-order> --}}
    <livewire:point-of-sales-kasir.modal-pembayaran-berhasil>
</x-page-layout-pos>
