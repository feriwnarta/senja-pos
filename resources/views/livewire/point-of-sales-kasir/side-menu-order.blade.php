<div>
    @if (request()->is('/pos/menu'))
        <div class="sidebar-order offcanvas offcanvas-end" tabindex="-1" id="sidebar-trigger{{ $menuSaved['id'] }}">
            <div class="sidebar-order-detail-content">
                <div class="order-detail-header d-flex flex-row justify-content-between align-items-center">
                    <h1 class="text-bold-20 color-f166">Rincian Pesanan</h1>
                    <a id="exit-shift-maincontent" class="order-detail-close text-medium-20 color-7575" data-bs-dismiss="offcanvas">&times;</a>
                </div>
                <div class="button-order-detail-wrapper">
                    <button type="button" class="button-meja text-medium-12 color-c2c2" data-bs-toggle="modal"
                        data-bs-target="#modalPilihMeja">Meja</button>
                    <button type="button" class="button-pelanggan text-medium-12 color-c2c2" data-bs-toggle="modal"
                        data-bs-target="#modalPilihPelanggan">Pelanggan</button>
                </div>
                <div class="line-divider-order-detail"></div>
                <div class="ordered-detail">
                    <h3 class="text-bold-16 color-0a0a">Dine-in</h3>
                    <div class="ordered-wrapper">
                        <div class="ordered-menu-text">
                            <div class="menu-ordered">
                                <p class="text-light-16 color-4040">{{ $menuSaved['menu_name'] }}</p>
                                <p class="text-light-12 color-7575">Variant, Size, Topping</p>
                            </div>
                        </div>
                        <div class="ordered-menu-number">
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                    </div>
                    <div class="payment-wrapper">
                        <div class="discount">
                            <span class="text-medium-16 color-4040">Discount</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="subtotal">
                            <span class="text-medium-16 color-4040">Subtotal</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="tax">
                            <span class="text-medium-16 color-4040">Services</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="rounding">
                            <span class="text-medium-16 color-4040">Rounding</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                    </div>
                    <div class="grand-total-wrapper">
                        <div class="line-divider-grand-total"></div>
                        <div class="grand-total">
                            <span class="text-bold-20 color-0a0a">Total</span>
                            <span class="text-bold-20 color-0a0a">Rp. 0</span>
                        </div>
                    </div>
                    <div class="button-order-detail-group">
                        <button type="button"
                            class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36">Simpan
                            Pesanan</button>
                        <div class="button-cetak-bayar">
                            <button type="button"
                                class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36">Cetak</button>
                            <button type="button" class="button-f166-inh text-medium-14 text-white p-8-16 h-36"
                                data-bs-toggle="modal" data-bs-target="#modalBayar">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="sidebar-order offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
            id="sidebar-trigger{{ $menuSaved['id'] }}">
            <div class="sidebar-order-detail-content">
                <div class="order-detail-header d-flex flex-row justify-content-between align-items-center">
                    <h1 class="text-bold-20 color-f166">Rincian Pesanan</h1>
                    <a class="order-detail-close text-medium-20 color-7575" data-bs-dismiss="offcanvas">&times;</a>
                </div>
                <div class="button-order-detail-wrapper">
                    <button type="button" class="button-meja text-medium-12 color-c2c2" data-bs-toggle="modal"
                        data-bs-target="#modalPilihMeja">Meja</button>
                    <button type="button" class="button-pelanggan text-medium-12 color-c2c2" data-bs-toggle="modal"
                        data-bs-target="#modalPilihPelanggan">Pelanggan</button>
                </div>
                <div class="line-divider-order-detail"></div>
                <div class="ordered-detail">
                    <h3 class="text-bold-16 color-0a0a">Dine-in</h3>
                    <div class="ordered-wrapper">
                        <div class="ordered-menu-text">
                            <div class="menu-ordered">
                                <p class="text-light-16 color-4040">{{ $menuSaved['menu_name'] }}</p>
                                <p class="text-light-12 color-7575">Variant, Size, Topping</p>
                            </div>
                        </div>
                        <div class="ordered-menu-number">
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                    </div>
                    <div class="payment-wrapper">
                        <div class="discount">
                            <span class="text-medium-16 color-4040">Discount</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="subtotal">
                            <span class="text-medium-16 color-4040">Subtotal</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="tax">
                            <span class="text-medium-16 color-4040">Services</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                        <div class="rounding">
                            <span class="text-medium-16 color-4040">Rounding</span>
                            <span class="text-medium-16 color-4040">0</span>
                        </div>
                    </div>
                    <div class="grand-total-wrapper">
                        <div class="line-divider-grand-total"></div>
                        <div class="grand-total">
                            <span class="text-bold-20 color-0a0a">Total</span>
                            <span class="text-bold-20 color-0a0a">Rp. 0</span>
                        </div>
                    </div>
                    <div class="button-order-detail-group">
                        <button type="button"
                            class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36">Simpan
                            Pesanan</button>
                        <div class="button-cetak-bayar">
                            <button type="button"
                                class="button-outline-f166-inh text-medium-14 color-f166 p-8-16 h-36">Cetak</button>
                            <button type="button" class="button-f166-inh text-medium-14 text-white p-8-16 h-36"
                                data-bs-toggle="modal" data-bs-target="#modalBayar">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <livewire:point-of-sales-kasir.modal-bayar-order>
        <livewire:point-of-sales-kasir.modal-disc-voucher>
            <livewire:point-of-sales-kasir.modal-pilih-pelanggan>
                <livewire:point-of-sales-kasir.modal-pelanggan-baru>
                    <livewire:point-of-sales-kasir.modal-pilih-meja>
</div>
