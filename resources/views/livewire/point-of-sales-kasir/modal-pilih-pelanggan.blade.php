<div class="modal-dialog modal-wrapper">
    <div class="modal-content">
        <div
            class="modal-header header-body-wrapper d-flex flex-row justify-content-between align-items-center sticky-top">
            <button type="button" class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 h-40" data-bs-dismiss="modal">Keluar</button>
            <h1 class="text-medium-20 color-4040 ls-176">5 Pelanggan</h1>
            <button type="button" class="button-f166-inh text-medium-16 text-white ls-176 p-8-16 h-40" data-bs-toggle="modal"
                data-bs-target="#modalPelangganBaru">Pelanggan Baru</button>
        </div>
        <div class="modal-body modal-pilihPelanggan-wrapper">
            <div class="body-select-pelanggan-wrapper">
                <form class="search-pelanggan-wrapper">
                    <input class="form-control h-32" type="search" placeholder="Pencarian" aria-label="Search">
                </form>
                <div class="list-pelanggan-wrapper">
                    <table class="table">
                        <tbody>
                            @for ($i = 0; $i < 3; $i++) <tr class="table-pelanggan-bordered">
                                <td class="text-light-14 color-6161"><i class="user-icon"></i>pelanggan <span>{{$i}}</span></td>
                                <td class="text-light-14 color-6161"><i class="phone-icon"></i>Phone Number <span>{{$i}}</span></td>
                                <td class="text-light-14 color-6161"><i class="mail-icon"></i>Customer Name <span>{{$i}}</span></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>