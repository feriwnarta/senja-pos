<h1 class="pos-text">
    {{ (request()->is('pos/menu') ? 'Point Of Sales' : request()->is('pos/active-order')) ? 'Pesanan Aktif' : (request()->is('pos/shift-aktif') ? 'Shift Aktif' : 'Riwayat Shift') }}
</h1>
