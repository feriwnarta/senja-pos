<div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header d-flex align-items-center">
        <a type="button" data-bs-dismiss="offcanvas" aria-label="Close"><img src="{{ asset('img/icons/x.png') }}" alt="X"></a>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <div class="sidebar-button-container">
                <button class="btn-clicked-active" type="button" href="#">Point Of Sales</a>
            </div>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Daftar Pesanan
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <li><button class="btn-clicked-active text-decoration-none" href="#">Action</a></li>
                        <li><button class="btn-clicked-active text-decoration-none" href="#">Another action</a></li>
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Shift & Transaksi Finansial
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <li><button class="btn-clicked-active" href="#">Action</a></li>
                        <li><button class="btn-clicked-active" href="#">Another action</a></li>
                        <li><button class="btn-clicked-active" href="#">Something else here</a></li>
                    </div>
                  </div>
                </div>
            </div>
            <div class="sidebar-button-container">
                <button class="btn-clicked-active" type="button">
                    Settings
                </button>
            </div>
        </ul>
    </div>
</div>
