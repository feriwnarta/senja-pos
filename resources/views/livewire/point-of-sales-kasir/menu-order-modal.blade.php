<div class="modal-dialog modal-wrapper modal-xl">
    <div class="modal-content">
        <div
            class="modal-header header-body-wrapper d-flex flex-column justify-content-center align-items-center sticky-top">
            <h1 class="modal-title menu-name" id="exampleModalLabel">Modal title</h1>
            <h1 class="modal-title menu-price" id="exampleModalLabel">Rp. 0</h1>
        </div>
        <form class="modal-body modal-body-wrapper d-flex flex-column justify-content-center align-items-center">
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Variasi</span>
                    <span class="divider"></span>
                    <span class="inline-text-2">Pilih Salah Satu</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body">
                    <button type="button" class="modal-variasi-button">Satu</button>
                    <button type="button" class="modal-variasi-button">Dua</button>
                    <button type="button" class="modal-variasi-button">Tiga</button>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Ukuran</span>
                    <span class="divider"></span>
                    <span class="inline-text-2">Pilih Salah Satu</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body">
                    <button type="button" class="modal-variasi-button">Satu</button>
                    <button type="button" class="modal-variasi-button">Dua</button>
                    <button type="button" class="modal-variasi-button">Tiga</button>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header-toping">
                    <div class="toping-left-content">
                        <span class="inline-text">Toping</span>
                        <span class="divider"></span>
                        <span class="inline-text-2">Pilih Salah Satu / Lebih Dari Satu</span>
                    </div>
                    <div class="toping-right-content">
                        <input type="checkbox" id="check-all" class="checkbox" onclick="checkAll(this)">
                        <label for="check-all"> Pilih Semua</label>
                    </div>
                </div>
                <div class="line-divider"></div>
                <div class="content-body-toping flex-column">
                    <div class="d-flex flex-row choose-multiple-wrapper">
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="satu"><span>Satu</span>
                            </label>
                        </div>
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="dua"><span>Dua</span>
                            </label>
                        </div>
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="tiga"><span>Tiga</span>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex flex-row choose-multiple-wrapper">
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="satu"><span>Satu</span>
                            </label>
                        </div>
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="dua"><span>Dua</span>
                            </label>
                        </div>
                        <div class="choose-multiple">
                            <label>
                                <input type="checkbox" id="toping" value="tiga"><span>Tiga</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Ukuran</span>
                    <span class="divider"></span>
                    <span class="inline-text-2">Pilih Salah Satu</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body">
                    <input type="number" class="modal-variasi-input text-end" placeholder="1" wire:model.live="count">
                    <button type="button" class="modal-variasi-button1"><i class="minus-icon"
                            wire:click="decrement"></i></button>
                    <button type="button" class="modal-variasi-button2"><i class="plus-icon"
                            wire:click="increment"></i></button>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Tipe Penjualan</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body-sell-type">
                    <button type="button" class="modal-variasi-button3">Dine in</button>
                    <button type="button" class="modal-variasi-button3">Take Away</button>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Note</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body">
                    <textarea class="form-control note-text-area" placeholder="" id="floatingTextarea2"></textarea>
                </div>
            </div>
            <div class="modal-content-wrapper">
                <div class="content-header">
                    <span class="inline-text">Tipe Penjualan</span>
                </div>
                <div class="line-divider"></div>
                <div class="content-body-sell-type">
                    <button type="button" class="modal-variasi-button3">Happy Hour 5%</button>
                    <button type="button" class="modal-variasi-button3">Happy Weekend 10%</button>
                </div>
            </div>
        </form>
        <div class="modal-variasi-footer">
            <button class="modal-cancel-button" type="button">Cancel</button>
            <button class="modal-save-button" type="button">Save</button>
        </div>
    </div>
</div>