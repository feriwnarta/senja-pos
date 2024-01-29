<div>
    <div class="modal fade" id="modalDiscVoucher" tabindex="-1" aria-labelledby="modalDiscVoucherLabel" aria-hidden="true"
        data-bs-backdrop="true">
        <div class="modal-dialog modal-wrapper">
            <div class="modal-content">
                <div
                    class="modal-header header-body-wrapper d-flex flex-row justify-content-center align-items-center sticky-top">
                    <h1 class="text-medium-20 color-4040">Tambah Diskon</h1>
                </div>
                <div class="modal-body modal-discvoc-wrapper">
                    <div class="body-select-pelanggan-wrapper">
                        <form class="input-discount-voucher d-flex flex-column">
                            <div class="d-flex flex-column gap-2">
                                <label class="text-bold-14 color-4040">Discount</label>
                                <div class="line-divider"></div>
                                <div class="radio-group-discount d-flex flex-row">
                                    <input type="radio" class="btn-check btn-outline-primary" name="options-base"
                                        id="10percent" autocomplete="off">
                                    <label class="btn" for="10percent">10%</label>
                                    <input type="radio" class="btn-check btn-outline-primary" name="options-base"
                                        id="20percent" autocomplete="off">
                                    <label class="btn" for="20percent">20%</label>
                                    <input type="radio" class="btn-check btn-outline-primary" name="options-base"
                                        id="30percent" autocomplete="off">
                                    <label class="btn" for="30percent">30%</label>
                                    <input type="radio" class="btn-check btn-outline-primary" name="options-base"
                                        id="50percent" autocomplete="off">
                                    <label class="btn" for="50percent">50%</label>
                                    <input type="radio" class="btn-check btn-outline-primary" name="options-base"
                                        id="100percent" autocomplete="off">
                                    <label class="btn" for="100percent">100%</label>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <label class="text-bold-14 color-4040">Vocer</label>
                                <div class="line-divider"></div>
                                <input class="form-control text-light-14 color-7575" type="text"
                                    placeholder="Masukan nomor vocer">
                            </div>
                        </form>
                        <div class="button-group-discvoc d-flex flex-row justify-content-end gap-3">
                            <button class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 ls-176 h-40"
                                data-bs-dismiss="modal">Keluar</button>
                            <button class="button-w119-f166 text-medium-16 text-white p-8-16 ls-176 h-40"
                                data-bs-toggle="modal" data-bs-target="#modalOtorisasiPin">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <livewire:point-of-sales-kasir.modal-pin>
</div>
