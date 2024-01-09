<x-page-layout-pos>
  <div class="active-order-wrapper">
    <div class="header-active-order">
      <h1 class="active-order-text">Pesanan Aktif</h1>
    </div>
    <table class="table table-borderless">
      <thead class="table-head">
        <tr>
          <th scope="col">Meja</th>
          <th scope="col">Nama</th>
          <th scope="col">Tipe</th>
        </tr>
      </thead>
      <tbody>
        @for ($i = 0; $i < 5; $i++) <tr class="table-row-bordered">
          <td>T17</td>
          <td>William</td>
          <td>Dine in</td>
          </tr>
          @endfor
      </tbody>
    </table>
  </div>
</x-page-layout-pos>