@php use Illuminate\Support\Facades\Route; @endphp
    <!-- Sidebar  -->

<nav id="sidebar">

    <div class="sidebar-logo">
        <img src="{{ asset('img/cahaya_senja_logo.png') }}" alt="logo cahaya senja" class="logo-sidebar">
    </div>

    <div class="button-menu">

        @php
            $collapseDashboard = Route::is(['summary'])
        @endphp

        <div class="dashboard-button">
            <div class="accordion" id="accordionMenu">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button button-icon-text description-1-medium {{ $collapseDashboard ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse" aria-expanded="{{ $collapseDashboard ? 'true': 'false' }}"
                            data-bs-target="#collapseDashboard">
                            <i class="dashboard-icon"></i>
                            Dashboard
                        </button>
                    </h2>
                    <div id="collapseDashboard"
                         class="accordion-collapse collapse {{ $collapseDashboard ? 'show' : '' }}">
                        <a href="/dashboard/summary" wire:navigate>
                            <div class="accordion-body">
                                <button
                                    class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('summary') ? 'inner-menu-active' : ''}}"
                                    id="">
                                    Ringkasan
                                </button>
                            </div>
                        </a>
                    </div>
                </div>

                @php
                    $collapseComposition = Route::is(['composition-item', 'composition-view-item', 'composition-create-item', 'category-item', 'category-view-item', 'add-category', 'detail-category', 'unit', 'add-unit', 'view-unit', 'recipe', 'view-recipe', 'create-recipe'])
                @endphp

                @canany(['item.view', 'category.view', 'unit.view', 'resep.view'])
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button button-icon-text description-1-medium {{ $collapseComposition ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="{{ $collapseComposition ? 'true': 'false' }}"
                                data-bs-target="#collapsePos">
                                <i class="composition-icon"></i>
                                {{ __('sidebar_locale.komposisi.name') }}
                            </button>
                        </h2>
                        <div id="collapsePos"
                             class="accordion-collapse collapse {{ $collapseComposition ? 'show' : '' }} "
                             data-bs-parent="#accordionMenu">
                            <div class="accordion-body">
                                @can('item.view')
                                    <a href="/composition/item" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('composition-item') || Route::is('composition-view-item') || Route::is('composition-create-item') ? 'inner-menu-active' : ''}}"
                                            type="button" id="">
                                            {{ __('sidebar_locale.komposisi.item') }}
                                        </button>
                                    </a>

                                @endcan
                                @can('category.view')
                                    <a href="/composition/category-item" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('category-item') || Route::is('category-view-item') || Route::is('add-category') ||  Route::is('detail-category')? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Category
                                        </button>
                                    </a>
                                @endcan
                            </div>
                            @can('unit.view')
                                <a href="/composition/unit" wire:navigate>
                                    <button
                                        class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('unit')|| Route::is('add-unit') || Route::is('view-unit') ? 'inner-menu-active' : ''}}"
                                        type="button"
                                        id="">
                                        {{ __('sidebar_locale.gudang.unit') }}
                                    </button>
                                </a>
                            @endcan
                            {{--                        <a href="/composition/decomposition" wire:navigate>--}}
                            {{--                            <button--}}
                            {{--                                class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('Dekomposisi')  ? 'inner-menu-active' : ''}}"--}}
                            {{--                                type="button"--}}
                            {{--                                id="">--}}
                            {{--                                Dekomposisi--}}
                            {{--                            </button>--}}
                            {{--                        </a>--}}
                            @can('resep.view')
                                <a href="/composition/recipe" wire:navigate>
                                    <button
                                        class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('recipe') || Route::is('view-recipe') || Route::is('create-recipe') ? 'inner-menu-active' : ''}}"
                                        type="button"
                                        id="">
                                        Resep
                                    </button>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcanany

                @php
                    $collapseInv = Route::is(['list-warehouse', 'stock', 'add-warehouse', 'view-request-stock', 'view-request-stock', 'edit-request-stock', 'detail-warehouse', 'warehouse-transaction', 'warehouse-add-transaction','warehouse-transaction-detail', 'warehouse-transaction-detail-receipt', 'warehouse-list-stock']);
                @endphp
                @canany(['stokitem.view', 'stokopname.view', 'daftargudang.view', 'transaksi.stokmasuk.view', 'transaksi.stokkeluar.view', 'transaction.permintaanstock.view'])
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
                                @can('stokitem.view')
                                    <a href="/warehouse/stock" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('warehouse-list-stock')  ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Stok item
                                        </button>
                                    </a>
                                @endcan
                                @can('stokopname.view')
                                    <a href="" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium"
                                            type="button"
                                            id="">
                                            Stok opname
                                        </button>
                                    </a>
                                @endcan
                                @can('daftargudang.view')
                                    <a href="/warehouse/list-warehouse" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-warehouse') ||  Route::is('add-warehouse') ||  Route::is('detail-warehouse') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            {{ __('sidebar_locale.gudang.daftarGudang') }}
                                        </button>
                                    </a>
                                @endcan
                                @canany(['transaksi.stokmasuk.view', 'transaksi.stokkeluar.view', 'transaction.permintaanstock.view'])
                                    <a href="/warehouse/transaction" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('warehouse-transaction') || Route::is('edit-request-stock') || Route::is('view-request-stock') || Route::is('warehouse-add-transaction') || Route::is('warehouse-transaction-detail') || Route::is('warehouse-transaction-detail') || Route::is('warehouse-transaction-detail-receipt') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Transaksi
                                        </button>
                                    </a>
                                @endcanany

                            </div>
                        </div>
                    </div>
                @endcanany

                @php
                    $collapseCk = Route::is(['list-central-kitchen', 'remaining-production-stock', 'view-central-kitchen', 'add-central-kitchen', 'central-kitchen-production', 'central-kitchen-production-detail']);
                @endphp

                @canany(['stoksisaproduksi.view', 'daftardapursentral.view', 'produksi.view'])
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
                                @can('stoksisaproduksi.view')
                                    <a href="/central-kitchen/remaining-production-stock" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('remaining-production-stock') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Stok sisa produksi
                                        </button>
                                    </a>
                                @endcan
                                @can('daftardapursentral.view')
                                    <a href="/central-kitchen/list-central-kitchen" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-central-kitchen')|| Route::is('view-central-kitchen') || Route::is('add-central-kitchen') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Daftar dapur sentral
                                        </button>
                                    </a>
                                @endcan
                                @can('produksi.view')
                                    <a href="/central-kitchen/production"
                                       wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('central-kitchen-production') || Route::is('central-kitchen-production-detail')  ? 'inner-menu-active' : '' }}"
                                            type="button">
                                            Produksi
                                        </button>
                                    </a>
                                @endcan

                            </div>
                        </div>
                    </div>
                @endcanany


                @php
                    $collapseOutlet = Route::is(['list-outlet', 'add-outlet']);
                @endphp

                @canany('daftaroutlet.view')
                    <div class="accordion-item">
                        <h2 class="accordion-header">

                            <button
                                class="accordion-button button-icon-text description-1-medium {{ $collapseOutlet ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                aria-expanded="{{ $collapseOutlet ? 'true': 'false' }}"
                                data-bs-target="#collapseOutlet">
                                <i class="outlet-icon"></i>
                                {{ __('sidebar_locale.outlet.name') }}
                            </button>
                        </h2>
                        <div id="collapseOutlet"
                             class="accordion-collapse collapse {{ $collapseOutlet ? 'show' : '' }} "
                             data-bs-parent="#accordionMenu">
                            <div class="accordion-body">

                                @can('daftaroutlet.view')
                                    <a href="/outlet/list-outlet" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('list-outlet') || Route::is('add-outlet') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Daftar Outlet
                                        </button>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endcanany

                @php
                    $collapsePurchase = Route::is(['supplier', 'create-supplier', 'purchase', 'purchase-detail', 'purchased-detail', 'purchase-request-detail']);
                @endphp

                @canany(['supplier.view', 'pesananpembelian.pembelian.view', 'pesananpembelian.permintaan.view'])
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button button-icon-text description-1-medium {{ $collapsePurchase ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="{{ $collapsePurchase ? 'true': 'false' }}"
                                data-bs-target="#collapsePurchasing">
                                <i class="purchasing-icon"></i>
                                {{ __('sidebar_locale.pembelian.name') }}
                            </button>
                        </h2>
                        <div id="collapsePurchasing"
                             class="accordion-collapse collapse {{ $collapsePurchase ? 'show' : '' }} "
                             data-bs-parent="#accordionMenu">
                            <div class="accordion-body">
                                @can('supplier.view')
                                    <a href="/supplier" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('supplier') || Route::is('create-supplier') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Pemasok
                                        </button>
                                    </a>
                                @endcan
                                @canany(['pesananpembelian.pembelian.view', 'pesananpembelian.permintaan.view'])
                                    <a href="/purchase" wire:navigate>
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium  {{ Route::is('purchase') || Route::is('purchased-detail') || Route::is('purchase-detail') || Route::is('purchase-request-detail') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Pesanan Pembelian
                                        </button>
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>
                @endcanany

                @canany('akutansi.view')
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
                @endcanany

                @canany('finansial.view')
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
                @endcanany

                @php
                    $collapseUser = Route::is(['user', 'create-user', 'permission', 'create-role']);
                @endphp

                @canany(['profile.view', 'user.view', 'permission.view'])
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button button-icon-text description-1-medium {{ $collapseUser ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse" aria-expanded="{{ $collapseUser ? 'true': 'false' }}"
                                data-bs-target="#collapseReport">
                                <i class="account-icon"></i>
                                Akun
                            </button>
                        </h2>
                        <div id="collapseReport" class="accordion-collapse collapse {{ $collapseUser ? 'show' : '' }} "
                             data-bs-parent="#accordionMenu">
                            <div class="accordion-body">
                                @can('profile.view')
                                    <a href="/user/profile">
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium"
                                            type="button"
                                            id="">
                                            Profile
                                        </button>
                                    </a>
                                @endcan
                                @can('user.view')
                                    <a href="/user">
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('user') || Route::is('create-user')  ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            User
                                        </button>
                                    </a>
                                @endcan
                                @can('permission.view')
                                    <a href="/user/permission">
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium {{ Route::is('permission') || Route::is('create-role') ? 'inner-menu-active' : ''}}"
                                            type="button"
                                            id="">
                                            Permission
                                        </button>
                                    </a>
                                @endcan
                                <form method="POST" action="{{ \route('logout') }}" x-data="">

                                    @csrf

                                    <a class=""
                                       href="{{ \route('logout') }}" @click.prevent="$root.submit();">
                                        <button
                                            class="btn button-sidebar-text-only-text description-1-medium"
                                            type="button"
                                            id="">
                                            Logout
                                        </button>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcanany

            </div>


        </div>
    </div>
</nav>


