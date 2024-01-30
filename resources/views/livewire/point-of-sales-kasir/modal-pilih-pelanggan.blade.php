<div class="modal fade" id="modalPilihPelanggan" tabindex="-1" aria-labelledby="modalPilihPelangganLabel" aria-hidden="true"
    data-bs-backdrop="true">
    <div class="modal-dialog modal-wrapper">
        <div class="modal-content">
            <div
                class="modal-header header-body-wrapper d-flex flex-row justify-content-between align-items-center sticky-top">
                <button type="button" class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 h-40"
                    data-bs-dismiss="modal">Keluar</button>
                <h1 class="text-medium-20 color-4040 ls-176">{{count($data)}} Pelanggan</h1>
                <button type="button" class="button-f166-inh text-medium-16 text-white ls-176 p-8-16 h-40"
                    data-bs-toggle="modal" data-bs-target="#modalPelangganBaru">Pelanggan Baru</button>
            </div>
            <div class="modal-body modal-pilihPelanggan-wrapper">
                <div class="body-select-pelanggan-wrapper">
                    <form class="search-pelanggan-wrapper">
                        <input class="form-control h-32" type="search" placeholder="Pencarian" aria-label="Search">
                    </form>
                    <div class="list-pelanggan-wrapper">
                        <table class="table">
                            <tbody>
                                @foreach ($data as $customer)
                                    <tr class="table-pelanggan-bordered">
                                        <td class="text-light-14 color-6161 w-100">
                                            <span class="text-start">
                                                <i class="user-icon"></i>
                                                {{ $customer->name }}</span>
                                        </td>
                                        <td class="text-light-14 color-6161 w-100">
                                            <span class="text-start">
                                                <i class="phone-icon"></i>
                                                {{ $customer->phoneNumber }}</span>
                                        </td>
                                        <td class="text-light-14 color-6161 w-100">
                                            <span class="text-start">
                                                <i class="mail-icon"></i>
                                                {{ $customer->emailAddress }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
