<h1 class="pos-text">
    {{  (request()->is('pos/menu') ? 'Point Of Sales' :
        (request()->is('pos/active-order') ? 'Pesanan Aktif' :
        (request()->is('pos/active-shift') ? 'Shift Aktif' :
        (request()->is('pos/riwayat-shift') ? 'Riwayat Shift' :
        'Mutasi')))) }}
</h1>
