<x-page-layout-pos>
    <div class="active-order-wrapper">
        <div class="header-mutasi">
            <livewire:components.navbar-kasir.header-kasir>
                <div class="tanggal-wrapper">
                    <div>
                        <label class="text-light-14 color-9e9e">Tanggal Mulai</label>
                        <input type="date" id="date" name="begin">
                    </div>
                    <div>
                        <label class="text-light-14 color-9e9e">Tanggal Selesai</label>
                        <input type="date" id="date" name="end">
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
                    <th class="text-light-14 color-4040" scope="col">No</th>
                    <th class="text-light-14 color-4040" scope="col">Tanggal</th>
                    <th class="text-light-14 color-4040" scope="col">Nomor Transaksi</th>
                    <th class="text-light-14 color-4040" scope="col">Meja</th>
                    <th class="text-light-14 color-4040" scope="col">Jumlah Total</th>
                    <th class="text-light-14 color-4040" scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i < 15; $i++)
                    <tr class="table-row-bordered">
                        <td class="text-light-14 color-4040">{{ $i }}</td>
                        <td class="text-light-14 color-4040">05/02/2023</td>
                        <td class="text-light-14 color-4040">CSCF0001</td>
                        <td class="text-light-14 color-4040">T12</td>
                        <td class="text-light-14 color-4040">Rp. 350.000</td>
                        <td class="text-light-14 color-4040">Lunas</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</x-page-layout-pos>
