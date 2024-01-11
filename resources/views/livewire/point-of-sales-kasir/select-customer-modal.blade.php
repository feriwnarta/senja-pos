<div class="modal-dialog modal-wrapper">
    <div class="modal-content">
        <div
            class="modal-header header-body-wrapper d-flex flex-row justify-content-between align-items-center sticky-top">
            <button type="button" class="button-keluar-pilih-customer" data-bs-dismiss="modal">Keluar</button>
            <h1 class="jumlah-pelanggan">5 Pelanggan</h1>
            {{-- <button type="button" class="button-tambah-pelanggan" data-bs-toggle="modal"
                data-bs-target="#modalPelangganBaru">Pelanggan Baru</button> --}}
        </div>
        <div class="modal-body modal-body-wrapper">
            <div class="body-select-pelanggan-wrapper">
                <form class="search-pelanggan-wrapper">
                    <input class="form-control" type="search" placeholder="Pencarian" aria-label="Search">
                </form>
                <div class="list-pelanggan-wrapper">
                    <table class="table">
                        <tbody>
                            @for ($i = 0; $i < 3; $i++) <tr class="table-pelanggan-bordered">
                                <td><i class="user-icon"></i>pelanggan <span>{{$i}}</span></td>
                                <td><i class="phone-icon"></i>Phone Number <span>{{$i}}</span></td>
                                <td><i class="mail-icon"></i>Customer Name <span>{{$i}}</span></td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal" id="modalPelangganBaru" tabindex="-1" aria-labelledby="modalPelangganBaruLabel"
        aria-hidden="true" data-bs-backdrop="false">
        <livewire:point-of-sales-kasir.modal-pelanggan-baru>
    </div> --}}
</div>