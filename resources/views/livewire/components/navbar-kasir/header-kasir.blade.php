<h1 class="pos-text">{{ (request()->is('pos/menu')) ? 'Point Of Sales' : ((request()->is('pos/active-order'))
    ?
    'Pesanan Aktif' :
    'Shift Aktif') }}</h1>