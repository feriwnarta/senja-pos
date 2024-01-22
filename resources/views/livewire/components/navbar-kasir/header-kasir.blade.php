<h1 class="text-bold-24 color-f166">
    {{  (request()->is('pos/menu') ? 'Point Of Sales' :
        (request()->is('pos/active-order') ? 'Pesanan Aktif' :
        (request()->is('pos/active-shift') ? 'Shift Aktif' :
        (request()->is('pos/active-shift-detail') ? 'Shift Aktif' :
        (request()->is('pos/riwayat-shift') ? 'Riwayat Shift' :
        (request()->is('pos/riwayat-shift-detail') ? 'Riwayat Shift' :
        'Mutasi')))))) }}
</h1>
