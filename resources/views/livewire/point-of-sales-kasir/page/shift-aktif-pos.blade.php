<x-page-layout-pos>
    <div class="active-shift-wrapper">
        <div class="header-active-shift">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <div class="input-shift-wrapper">
            <form class="form-input-shift">
                <div class="input-saldo-shift">
                    <label>Saldo Tunai</label>
                    <input type="text" class="form-control" placeholder="Masukan Jumlah Saldo">
                </div>
                <div class="input-pin-shift">
                    <label>Input Pin</label>
                    <input type="password" class="form-control" placeholder="Masukan PIN">
                </div>
                <div class="button-group-shift">
                    <button class="button-mulai-shift" href="/pos/active-shift-detail" wire:navigate>Mulai Shift</button>
                </div>
            </form>
        </div>
    </div>
</x-page-layout-pos>