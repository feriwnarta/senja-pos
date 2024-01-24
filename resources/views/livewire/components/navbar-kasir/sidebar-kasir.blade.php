<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
  id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
  <div class="offcanvas-header d-flex align-items-center">
    <a type="button" data-bs-dismiss="offcanvas" aria-label="Close"><i class="x-icon"></i></a>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <div class="sidebar-button-container">
        <button id="pos-menu" class="btn-clicked-active sidebar-text" type="button" href="/pos/menu" wire:navigate><i
            class="cash-icon"></i>Point Of Sales</button>
      </div>
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed sidebar-text" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              <i class="clipboard-icon"></i>
              Daftar Pesanan
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse sidebar-text" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <li><button class="btn-clicked-active text-decoration-none sidebar-text" href="/pos/active-order"
                  wire:navigate>Pesanan Aktif</button></li>
              <li><button class="btn-clicked-active text-decoration-none sidebar-text" href="#">Riwayat Pesanan</button>
              </li>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed sidebar-text" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              <i class="clock-icon"></i>
              Shift & Transaksi Finansial
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <li><button class="btn-clicked-active sidebar-text" href="/pos/active-shift" wire:navigate>Shift Aktif</button>
              </li>
              <li><button class="btn-clicked-active sidebar-text" href="/pos/riwayat-shift" wire:navigate>Riwayat
                  Shift</button></li>
              <li><button class="btn-clicked-active sidebar-text" href="/pos/mutasi" wire:navigate>Mutasi</button></li>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar-button-container sidebar-text">
        <button class="btn-clicked-active" type="button">
          <i class="setting-icon"></i>Settings
        </button>
      </div>
    </ul>
  </div>
</div>