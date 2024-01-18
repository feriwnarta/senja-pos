<x-page-layout-pos>
    <div class="riwayat-shift-detail-wrapper">
        <div class="active-shift-detail-header">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <div class="riwayat-shift-detail-content">
            <h1 class="tanggal-shift-detail">Minggu, 5 Februari 2023</h1>
            {{-- Shift Detail --}}
            <div class="shift-detail-content">
                {{-- Shift Detail Content --}}
                <h2 class="shift-detail-text">Shift Detail</h2>
                <div class="d-flex flex-column gap-2">
                    <div class="shift-detail-subcontent">
                        <span>Nama</span>
                        <span>Andre</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Outlet</span>
                        <span>Cahaya Senja Cafe</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Mulai Shift</span>
                        <span>08:00 WIB</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Akhir Shift</span>
                        <span>15:00 WIB</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Sold Items</span>
                        <span>20</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Refund Items</span>
                        <span>0</span>
                    </div>
                </div>
            </div>
            <div class="line-divider"></div>
            {{-- Cash Management --}}
            <div class="shift-detail-content">
                {{-- Cash Management Content --}}
                <h2 class="shift-detail-text">Cash Management</h2>
                <div class="d-flex flex-column gap-2">
                    <div class="shift-detail-subcontent">
                        <span>Starting Cash</span>
                        <span>2.000.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Cash Payment</span>
                        <span>1.800.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Kas Keluar</span>
                        <span>70.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Kas Masuk</span>
                        <span>0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Saldo Akhir</span>
                        <span>3.730.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Saldo Aktual</span>
                        <span>3.600.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span>Selisih</span>
                        <span>130.000</span>
                    </div>
                </div>
            </div>
            <div class="line-divider"></div>
            {{-- Transaction Detail --}}
            <div class="shift-detail-content">
                {{-- Transaction Detail Content --}}
                <h2 class="shift-detail-text">Transaction Detail</h2>
                <div class="sold-item-detail">
                    <h3 class="shift-detail-subtext">Sold Item</h3>
                    {{-- DUMMY DATA FOR SOLD ITEM --}}
                    @php
                        $arrayMenu = [
                            [
                                'id' => 1,
                                'name' => 'Chamie Seafood XO',
                                'itemSold' => 8,
                                'price' => 80000,
                            ],
                            [
                                'id' => 2,
                                'name' => 'Nasi Goreng XO',
                                'itemSold' => 1,
                                'price' => 70000,
                            ],
                            [
                                'id' => 3,
                                'name' => 'Bubur Hot Pot',
                                'itemSold' => 4,
                                'price' => 150000,
                            ],
                            [
                                'id' => 4,
                                'name' => 'Ayam Pak Cham Kee',
                                'itemSold' => 8,
                                'price' => 250000,
                            ],
                            [
                                'id' => 5,
                                'name' => 'Kopi Susu Senja',
                                'itemSold' => 6,
                                'price' => 30000,
                            ],
                            [
                                'id' => 6,
                                'name' => 'Kopi Susu Senja',
                                'itemSold' => 8,
                                'price' => 30000,
                            ],
                        ];
                        $totalSold = 0;

                        foreach ($arrayMenu as $data) {
                            $totalSold += $data['price'] * $data['itemSold'];
                        }
                    @endphp
                    @foreach ($arrayMenu as $item)
                        <div>
                            <div class="shift-detail-subcontent2" wire:key="{{ $item['id'] }}">
                                <div class="nama-menu-text">
                                    {{ $item['name'] }}
                                </div>
                                <div class="menu-terjual">
                                    <span>{{ $item['itemSold'] }} x {{ $item['price'] }}</span>
                                    <span>{{ $item['itemSold'] * $item['price'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="total-terjual">
                    <span>Total</span>
                    <span>{{ $totalSold }}</span>
                </div>

            </div>
            <div class="line-divider"></div>
            {{-- Payment Detail --}}
            <div class="shift-detail-content">
                {{-- Payment Detail Content --}}
                <h2 class="shift-detail-text">Payment Detail</h2>
                <div class="shift-detail-subcontent">
                    <span>Nama</span>
                    <span>Andre</span>
                </div>
                <div class="shift-detail-subcontent">
                    <span>Outlet</span>
                    <span>Cahaya Senja Cafe</span>
                </div>
                <div class="shift-detail-subcontent">
                    <span>Mulai Shift</span>
                    <span>08:00</span>
                </div>
                <div class="shift-detail-subcontent">
                    <span>Akhir Shift</span>
                    <span>15:00</span>
                </div>
                <div class="shift-detail-subcontent">
                    <span>Sold Items</span>
                    <span>20</span>
                </div>
                <div class="shift-detail-subcontent">
                    <span>Refund Items</span>
                    <span>0</span>
                </div>
            </div>
        </div>
    </div>
</x-page-layout-pos>
