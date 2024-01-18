<x-page-layout-pos>
    <div class="active-order-wrapper">
      <div class="header-active-order">
        <livewire:components.navbar-kasir.header-kasir>
      </div>
      <table class="table table-borderless">
        <thead class="table-head">
          <tr>
            <th scope="col">Nama</th>
            <th scope="col">Mulai Shift</th>
            <th scope="col">Akhir Shift</th>
            <th scope="col">Total Ekspektasi</th>
            <th scope="col">Total Aktual</th>
            <th scope="col">Selisih</th>
          </tr>
        </thead>
        <tbody>
          @for ($i = 0; $i < 5; $i++) <tr class="table-row-bordered" wire:click="redirectRiwayatDetail()">
            <td>Andre</td>
            <td>05/02/2023 08:00</td>
            <td>05/02/2023 15:00</td>
            <td>Rp. 3.500.000</td>
            <td>Rp. 3.000.000</td>
            <td>Rp. 500.000</td>
            </tr>
            @endfor
        </tbody>
      </table>
    </div>
  </x-page-layout-pos>
