<x-page-layout-pos>
    <div class="active-shift-wrapper">
        <div class="header-active-shift">
            <livewire:components.navbar-kasir.header-kasir>
        </div>
        <div class="input-shift-wrapper">
            <form class="form-input-shift">
                <div class="input-saldo-shift">
                    <label class="text-light-14 color-4040">Saldo Tunai</label>
                    <input type="text" class="form-control" placeholder="Masukan Jumlah Saldo">
                </div>
                <div class="input-pin-shift">
                    <label class="text-light-14 color-4040">Input Pin</label>
                    <input type="password" class="form-control" placeholder="Masukan PIN">
                </div>
                <div class="button-group-shift">
                    <button class="button-w255-f166 text-medium-16 text-white p-8-16 h-40" href="/pos/active-shift-detail" wire:navigate>Mulai Shift</button>
                </div>
            </form>
        </div>
    </div>
</x-page-layout-pos>