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
                    <tr class="table-row-bordered">
                        <td class="text-light-14 color-6161">T17</td>
                        <td class="text-light-14 color-6161">William</td>
                        <td class="text-light-14 color-6161">Dine in</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div>
            <button class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36" data-bs-toggle="modal"
                data-bs-target="#modalDiscVoucher">Diskon &
                Vocer</button>
            <button class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36" data-bs-toggle="modal"
                data-bs-target="#modalBayar">Bayar</button>
            <button class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36" data-bs-toggle="modal"
                data-bs-target="#modalPembayaranBerhasil">Pembayaran Sukses</button>
            <button class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36" data-bs-toggle="modal"
                data-bs-target="#modalOtorisasiPin">Pin</button>
            <button class="btn btn-primary">Tambah Pesanan</button>
            <button class="btn btn-primary">Cetak Pesanan</button>
        </div>
    </div>
    <div class="modal fade" id="modalDiscVoucher" tabindex="-1" aria-labelledby="modalDiscVoucherLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <livewire:point-of-sales-kasir.modal-disc-voucher>
    </div>
    <div class="modal fade" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true"
        data-bs-backdrop="true">
        <livewire:point-of-sales-kasir.modal-bayar-order>
    </div>
    <div class="modal fade" id="modalPembayaranBerhasil" tabindex="-1" aria-labelledby="modalPembayaranBerhasilLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <livewire:point-of-sales-kasir.modal-pembayaran-berhasil>
    </div>
    <livewire:point-of-sales-kasir.modal-pin>
</x-page-layout-pos>
