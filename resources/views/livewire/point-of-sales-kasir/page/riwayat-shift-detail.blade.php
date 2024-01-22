<x-page-layout-pos>
    <div class="riwayat-shift-detail-wrapper">
        <div class="active-shift-detail-header">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <div class="riwayat-shift-detail-content">
            <h1 class="text-bold-24 color-4040">Minggu, 5 Februari 2023</h1>
            {{-- Shift Detail --}}
            <div class="shift-detail-content">
                {{-- Shift Detail Content --}}
                <h2 class="text-bold-20 color-4040">Shift Detail</h2>
                <div class="d-flex flex-column gap-2">
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Nama</span>
                        <span class="text-light-16 color-7575">Andre</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Outlet</span>
                        <span class="text-light-16 color-7575">Cahaya Senja Cafe</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Mulai Shift</span>
                        <span class="text-light-16 color-7575">08:00 WIB</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Akhir Shift</span>
                        <span class="text-light-16 color-7575">15:00 WIB</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Sold Items</span>
                        <span class="text-light-16 color-7575">20</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Refund Items</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                </div>
            </div>
            <div class="line-divider"></div>
            {{-- Cash Management --}}
            <div class="shift-detail-content">
                {{-- Cash Management Content --}}
                <h2 class="text-bold-20 color-4040">Cash Management</h2>
                <div class="d-flex flex-column gap-2">
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Starting Cash</span>
                        <span class="text-light-16 color-7575">2.000.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Cash Payment</span>
                        <span class="text-light-16 color-7575">1.800.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Kas Keluar</span>
                        <span class="text-light-16 color-7575">70.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Kas Masuk</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Saldo Akhir</span>
                        <span class="text-light-16 color-7575">3.730.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Saldo Aktual</span>
                        <span class="text-light-16 color-7575">3.600.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-medium-16 color-4040">Selisih</span>
                        <span class="text-light-16 color-7575">130.000</span>
                    </div>
                </div>
            </div>
            <div class="line-divider"></div>
            {{-- Transaction Detail --}}
            <div class="shift-detail-content">
                {{-- Transaction Detail Content --}}
                <h2 class="text-bold-20 color-4040">Transaction Detail</h2>
                <div class="sold-item-detail">
                    <h3 class="text-bold-16 color-4040">Sold Item</h3>
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
                                <div class="text-medium-16 color-4040">
                                    {{ $item['name'] }}
                                </div>
                                <div class="menu-terjual">
                                    <span class="text-light-16 color-4040">{{ $item['itemSold'] }} x {{ $item['price'] }}</span>
                                    <span class="text-light-16 color-7575">{{ $item['itemSold'] * $item['price'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="total-terjual">
                    <span class="text-bold-16 color-4040">Total</span>
                    <span class="text-light-16 color-7575">{{ $totalSold }}</span>
                </div>

            </div>
            <div class="line-divider"></div>
            {{-- Payment Detail --}}
            <div class="shift-detail-content">
                {{-- Payment Detail Content --}}
                <h2 class="text-bold-20 color-4040">Payment Detail</h2>
                <div class="payment-detail-content">
                    <h3 class="text-bold-16 color-4040">Cash Payment</h3>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Cash Sale</span>
                        <span class="text-light-16 color-7575">1.800.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Cash Invoice</span>
                        <span class="text-light-16 color-7575">70.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Cash Refund</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="total-terjual">
                        <span class="text-light-16 color-4040">Total</span>
                        <span class="text-light-16 color-7575">1.870.000</span>
                    </div>
                </div>
                <div class="payment-detail-content">
                    <h3 class="text-bold-16 color-4040">EDC</h3>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">BCA</span>
                        <span class="text-light-16 color-7575">750.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Mandiri</span>
                        <span class="text-light-16 color-7575">89.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">BNI</span>
                        <span class="text-light-16 color-7575">120.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Lainnya</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="total-terjual">
                        <span class="text-bold-16 color-4040">Total</span>
                        <span class="text-light-16 color-7575">959.000</span>
                    </div>
                </div>
                <div class="payment-detail-content">
                    <h3 class="text-bold-16 color-4040">Other Payment</h3>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Gopay</span>
                        <span class="text-light-16 color-7575">350.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Shopee Pay</span>
                        <span class="text-light-16 color-7575">400.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">OVO</span>
                        <span class="text-light-16 color-7575">80.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Bank Transfer</span>
                        <span class="text-light-16 color-7575">150.000</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Lainnya</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="total-terjual">
                        <span class="text-bold-16 color-4040">Total</span>
                        <span class="text-light-16 color-7575">980.000</span>
                    </div>
                </div>
                <div class="payment-detail-content">
                    <h3 class="text-bold-16 color-4040">Online Delivery</h3>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Gofood</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Grab Food</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Shopee Food</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Traveloka Eats</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="shift-detail-subcontent">
                        <span class="text-light-16 color-4040">Lainnya</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                    <div class="total-terjual">
                        <span class="text-bold-16 color-4040">Total</span>
                        <span class="text-light-16 color-7575">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-page-layout-pos>
