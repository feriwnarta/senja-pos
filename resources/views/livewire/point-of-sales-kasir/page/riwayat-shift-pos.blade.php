<x-page-layout-pos>
    <div class="active-order-wrapper">
      <div class="header-active-order">
        <livewire:components.navbar-kasir.header-kasir>
      </div>
      <table class="table table-borderless">
        <thead class="table-head">
          <tr>
            <th class="text-light-14 color-4040" scope="col">Nama</th>
            <th class="text-light-14 color-4040" scope="col">Mulai Shift</th>
            <th class="text-light-14 color-4040" scope="col">Akhir Shift</th>
            <th class="text-light-14 color-4040" scope="col">Total Ekspektasi</th>
            <th class="text-light-14 color-4040" scope="col">Total Aktual</th>
            <th class="text-light-14 color-4040" scope="col">Selisih</th>
          </tr>
        </thead>
        <tbody>
          @for ($i = 0; $i < 5; $i++) <tr class="table-row-bordered" wire:click="redirectRiwayatDetail()">
            <td class="text-light-14 color-4040">Andre</td>
            <td class="text-light-14 color-4040">05/02/2023 08:00</td>
            <td class="text-light-14 color-4040">05/02/2023 15:00</td>
            <td class="text-light-14 color-4040">Rp. 3.500.000</td>
            <td class="text-light-14 color-4040">Rp. 3.000.000</td>
            <td class="text-light-14 color-4040">Rp. 500.000</td>
            </tr>
            @endfor
        </tbody>
      </table>
    </div>
  </x-page-layout-pos>
