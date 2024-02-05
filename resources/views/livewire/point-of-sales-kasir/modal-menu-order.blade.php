<div>
    <div class="modal fade" id="modalOrderMenu{{ $menuSelected['id'] }}" tabindex="-1"
        aria-labelledby="modalOrderMenuLabel{{ $menuSelected['id'] }}" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-wrapper">
            <div class="modal-content">
                <div
                    class="modal-header header-body-wrapper d-flex flex-column justify-content-center align-items-center sticky-top">
                    <h1 class="modal-title text-medium-20 color-6161" id="modalOrderMenuLabel">
                        {{ $menuSelected['menu_name'] }}</h1>
                    <h1 class="modal-title text-bold-20 color-4040" id="modalOrderMenuLabel">Rp. 0</h1>
                </div>
                <form class="modal-body modal-menu-wrapper">
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Variasi</span>
                            <span class="divider"></span>
                            <span class="text-light-12 color-9e9e">Pilih Salah Satu</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body">
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Ayam</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Pork</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Scallop</button>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Ukuran</span>
                            <span class="divider"></span>
                            <span class="text-light-12 color-9e9e">Pilih Salah Satu</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body">
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Kecil</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Sedang</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Besar</button>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header-toping">
                            <div class="toping-left-content">
                                <span class="text-bold-14 color-6161">Toping</span>
                                <span class="divider"></span>
                                <span class="text-light-12 color-9e9e">Pilih Salah Satu / Lebih Dari Satu</span>
                            </div>
                            <div class="toping-right-content">
                                <input type="checkbox" id="check-all" class="checkbox" onclick="checkAll(this)">
                                <label for="check-all" class="text-light-12 color-9e9e">Pilih Semua</label>
                            </div>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body-toping flex-column">
                            <div class="d-flex flex-row choose-multiple-wrapper">
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="satu"><span
                                            class="text-medium-14 color-9e9e">Udang</span>
                                    </label>
                                </div>
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="dua"><span
                                            class="text-medium-14 color-9e9e">Ayam</span>
                                    </label>
                                </div>
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="tiga"><span
                                            class="text-medium-14 color-9e9e">Ikan</span>
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-row choose-multiple-wrapper">
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="satu"><span
                                            class="text-medium-14 color-9e9e">Cumi</span>
                                    </label>
                                </div>
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="dua"><span
                                            class="text-medium-14 color-9e9e">Telur Pitan</span>
                                    </label>
                                </div>
                                <div class="choose-multiple">
                                    <label>
                                        <input type="checkbox" id="toping" value="tiga"><span
                                            class="text-medium-14 color-9e9e">Telur Asin</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Jumlah</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body" x-data="{ count: 1 }">
                            <input type="number" class="modal-variasi-input text-light-14 color-7575 h-32 text-end"
                                placeholder="1" x-model="count" disabled>
                            <button type="button" class="button-outline-f166-inh p-6 h-32"
                                @click="count <= 1 ? count + 1 : count--"><span class="minus-icon"></span></button>
                            <button type="button" class="button-f166-inh p-6 h-32" @click="count++"><span
                                    class="plus-icon"></span></button>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Tipe Penjualan</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body">
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Dine
                                in</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Take
                                Away</button>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Note</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body">
                            <textarea class="form-control note-text-area" placeholder="" id="floatingTextarea2"></textarea>
                        </div>
                    </div>
                    <div class="modal-content-wrapper">
                        <div class="content-header">
                            <span class="text-bold-14 color-6161">Discount</span>
                        </div>
                        <div class="line-divider"></div>
                        <div class="content-body">
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Happy
                                Hour
                                5%</button>
                            <button type="button"
                                class="button-outline-e0e0-inh text-medium-14 color-9e9e p-6-16 h-32">Happy
                                Weekend
                                10%</button>
                        </div>
                    </div>
                    <div class="modal-variasi-footer">
                        <button class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 ls-176 h-40"
                            type="button" data-bs-dismiss="modal">Cancel</button>
                        {{-- x-data wire:click="$dispatch('sidemenu-open')" --}}
                        {{-- {{ `$menuSelected['id']` }} --}}
                        {{-- @click="$dispatch('sidemenu-open')" --}}
                        <button id="shift-maincontent"
                            class="button-w119-f166 text-medium-16 text-white p-6-16 ls-176 h-40"
                            data-bs-target="#sidebar-trigger{{ $menuSelected['id'] }}" data-bs-toggle="offcanvas"
                            type="button">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <livewire:point-of-sales-kasir.side-menu-order :dataMenu="$menuSelected" wire:key="{{ $menuSelected['id'] }}">
</div>
