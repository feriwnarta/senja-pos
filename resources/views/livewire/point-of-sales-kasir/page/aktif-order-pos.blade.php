<x-page-layout-pos>
    <div class="active-order-wrapper">
        <div class="header-active-order">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <table class="table table-borderless">
            <thead class="table-head">
                <tr>
                    <th scope="col">Meja</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tipe</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 5; $i++)
                    <tr class="table-row-bordered">
                        <td>T17</td>
                        <td>William</td>
                        <td>Dine in</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDiscVoucher">Diskon &
                Vocer</button>
            <button class="btn btn-primary">Tambah Pesanan</button>
            <button class="btn btn-primary">Cetak Pesanan</button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBayar">Bayar</button>
        </div>
    </div>
    <div class="modal" id="modalDiscVoucher" tabindex="-1" aria-labelledby="modalDiscVoucherLabel" aria-hidden="true"
        data-bs-backdrop="true">
        <livewire:point-of-sales-kasir.modal-disc-voucher>
    </div>
    <div class="modal" id="modalBayar" tabindex="-1" aria-labelledby="modalBayarLabel" aria-hidden="true"
        data-bs-backdrop="true">
        <livewire:point-of-sales-kasir.modal-bayar-order>
    </div>
</x-page-layout-pos>
