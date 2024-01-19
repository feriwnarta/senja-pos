<x-page-layout-pos>
    <div class="active-order-wrapper">
        <div class="header-mutasi">
            <livewire:components.navbar-kasir.header-kasir>
                <div class="tanggal-wrapper">
                    <div>
                        <label>Tanggal Mulai</label>
                        <input type="date" id="date" name="begin" placeholder="dd/mm/yyyy">
                    </div>
                    <div>
                        <label>Tanggal Selesai</label>
                        <input type="date" id="date" name="end" placeholder="dd/mm/yyyy">
                    </div>
                    <div class="dropdown-center">
                        <button class="btn-ekspor dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Ekspor
                        </button>
                        <ul class="dropdown-menu" data-bs-theme="light">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Action two</a></li>
                            <li><a class="dropdown-item" href="#">Action three</a></li>
                        </ul>
                    </div>
                </div>
        </div>
        <table class="table table-borderless">
            <thead class="table-head">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nomor Transaksi</th>
                    <th scope="col">Meja</th>
                    <th scope="col">Jumlah Total</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i < 15; $i++)
                    <tr class="table-row-bordered">
                        <td>{{ $i }}</td>
                        <td>05/02/2023</td>
                        <td>CSCF0001</td>
                        <td>T12</td>
                        <td>Rp. 350.000</td>
                        <td>Lunas</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</x-page-layout-pos>
