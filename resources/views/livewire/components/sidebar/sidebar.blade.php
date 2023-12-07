@php use Illuminate\Support\Facades\Route; @endphp
    <!-- Sidebar  -->

<nav id="sidebar">

    <div class="sidebar-logo">
        <img src="{{ asset('img/cahaya_senja_logo.png') }}" alt="logo cahaya senja" class="logo-sidebar">
    </div>

    <div class="button-menu">

        <div class="dashboard-btn">

            <div class="accordion" id="accordionMenu">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <a href="/pos/dashboard" wire:navigate>
                            <button class="accordion-button button-icon-text description-1-medium collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseDashboard"
                                    aria-expanded="false">
                                <i class="pos-icon"></i>
                                Dashboard
                            </button>
                        </a>
                    </h2>
                    <div id="collapseDashboard" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                    id="">
                                Ringkasan
                            </button>
                        </div>
                    </div>
                </div>

                @php
                    $collapseComposition = Route::is(['composition-item', 'composition-create-item', 'category-item', 'add-category', 'detail-category', 'unit', 'add-unit', 'detail-unit', 'recipe', 'create-recipe']);
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button button-icon-text description-1-medium {{ $collapseComposition ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse" aria-expanded="{{ $collapseComposition ? 'true': 'false' }}"
                            data-bs-target="#collapsePos">
                            <i class="pos-icon"></i>
                            {{ __('sidebar_locale.komposisi.name') }}
                        </button>
                    </h2>
                    <div id="collapsePos" class="accordion-collapse collapse {{ $collapseComposition ? 'show' : '' }} "
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <a href="/composition/item" wire:navigate>
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('composition-item') || Route::is('composition-create-item') ? 'inner-menu-active' : ''}}"
                                    type="button" id="">
                                    {{ __('sidebar_locale.komposisi.item') }}
                                </button>
                            </a>
                            <a href="/composition/category-item" wire:navigate>
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('category-item') ||  Route::is('add-category') ||  Route::is('detail-category')? 'inner-menu-active' : ''}}"
                                    type="button"
                                    id="">
                                    Category
                                </button>
                            </a>
                        </div>
                        <a href="/composition/unit" wire:navigate>
                            <button
                                class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('unit') ? 'inner-menu-active' : ''}}"
                                type="button"
                                id="">
                                {{ __('sidebar_locale.gudang.unit') }}
                            </button>
                        </a>
                        <a href="/composition/recipe" wire:navigate>
                            <button
                                class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('recipe') || Route::is('create-recipe') ? 'inner-menu-active' : ''}}"
                                type="button"
                                id="">
                                Resep
                            </button>
                        </a>
                    </div>
                </div>


                @php
                    $collapseInv = Route::is(['list-warehouse', 'stock', 'add-warehouse', 'detail-warehouse']);
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button button-icon-text description-1-medium {{ $collapseInv ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ $collapseInv ? 'true': 'false' }}"
                            data-bs-target="#collapseInventory">
                            <i class="inventory-icon"></i>
                            {{ __('sidebar_locale.gudang.name') }}
                        </button>

                    </h2>


                    <div id="collapseInventory"
                         class="accordion-collapse collapse {{ $collapseInv ? 'show' : '' }} "
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                            <a href="/warehouse/list-warehouse" wire:navigate>
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-warehouse') ||  Route::is('add-warehouse') ||  Route::is('detail-warehouse') ? 'inner-menu-active' : ''}}"
                                    type="button"
                                    id="">
                                    {{ __('sidebar_locale.gudang.daftarGudang') }}
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    {{ __('sidebar_locale.gudang.itemKeluar') }}
                                </button>
                            </a>
                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text" type="button" id="">
                                    {{ __('sidebar_locale.gudang.itemMasuk') }}
                                </button>
                            </a>
                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    {{ __('sidebar_locale.gudang.permintaanPembelian') }}
                                </button>
                            </a>
                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    {{ __('sidebar_locale.gudang.permintaanProduksi') }}
                                </button>
                            </a>
                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    {{ __('sidebar_locale.gudang.laporanGudang') }}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>


                @php
                    $collapseCk = Route::is(['list-central-kitchen', 'add-central-kitchen']);
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button button-icon-text description-1-medium {{ $collapseCk ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ $collapseCk ? 'true': 'false' }}"
                            data-bs-target="#collapseCentralKitchen">
                            <i class="central-kitchen-icon"></i>
                            {{ __('sidebar_locale.dapurSentral.name') }}
                        </button>
                    </h2>
                    <div id="collapseCentralKitchen"
                         class="accordion-collapse collapse {{ $collapseCk ? 'show' : '' }} "
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <a href="/central-kitchen/list-central-kitchen" wire:navigate>
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-central-kitchen') || Route::is('add-central-kitchen') ? 'inner-menu-active' : ''}}"
                                    type="button"
                                    id="">
                                    Daftar central kitchen
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Stok
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Produksi
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Resep
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Permintaan Item
                                </button>
                            </a>

                            <a href="#" wire:navigate>
                                <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                        id="">
                                    Laporan
                                </button>
                            </a>
                        </div>
                    </div>
                </div>


                @php
                    $collapseOutlet = Route::is(['list-outlet', 'add-outlet']);
                @endphp

                <div class="accordion-item">
                    <h2 class="accordion-header">

                        <button
                            class="accordion-button button-icon-text description-1-medium {{ $collapseOutlet ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            aria-expanded="{{ $collapseOutlet ? 'true': 'false' }}"
                            data-bs-target="#collapseOutlet">
                            <i class="inventory-icon"></i>
                            {{ __('sidebar_locale.outlet.name') }}
                        </button>
                    </h2>
                    <div id="collapseOutlet"
                         class="accordion-collapse collapse {{ $collapseOutlet ? 'show' : '' }} "
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <a href="/outlet/list-outlet" wire:navigate>
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-outlet') || Route::is('add-outlet') ? 'inner-menu-active' : ''}}"
                                    type="button"
                                    id="">
                                    Daftar Outlet
                                </button>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="false"
                                data-bs-target="#collapsePurchasing">
                            <i class="purchasing-icon"></i>
                            {{ __('sidebar_locale.pembelian.name') }}
                        </button>
                    </h2>
                    <div id="collapsePurchasing" class="accordion-collapse collapse"
                         data-bs-parent="#accordionMenu">
                        <div class="accordion-body">
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                    id="">
                                Supplier
                            </button>
                            <button class="btn button-sidebar-text-only-text description-1-medium" type="button"
                                    id="">
                                Purchase Order
                            </button>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseAccount">
                            <i class="accounting-icon"></i>
                            {{ __('sidebar_locale.akuntansi.name') }}
                        </button>
                    </h2>
                    <div id="collapseAccount" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseFinance">
                            <i class="finance-icon"></i>
                            {{ __('sidebar_locale.finansial.name') }}
                        </button>
                    </h2>
                    <div id="collapseFinance" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button button-icon-text description-1-medium collapsed"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseReport">
                            <i class="report-icon"></i>
                            {{ __('sidebar_locale.user.name') }}
                        </button>
                    </h2>
                    <div id="collapseReport" class="accordion-collapse collapse" data-bs-parent="#accordionMenu">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>
</nav>


