<div id="content-loaded">
    <div class="row">
        @if(!$notFound)
            <div class="col-sm-4 offset-1">
                @if($mode != 'view')
                    {{-- KODE UNIT --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="KU004"

                               wire:model.live.debounce.600ms="code">
                        @error('code') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>

                    {{-- NAMA UNIT --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Btl (Botol)"

                               wire:model.live.debounce.600ms="name">
                        @error('name') <span text-xs text-red-600>{{ $message }}</span> @enderror

                    </div>
                @else
                    {{-- KODE UNIT --}}
                    <div class="container-input-default">
                        <label for="warehouseInput"
                               class="form-label input-label">Kode Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="KU004"
                               value="{{ $unit->code }}" disabled>

                    </div>

                    {{-- NAMA UNIT --}}
                    <div class="container-input-default margin-top-24">
                        <label for="warehouseInput"
                               class="form-label input-label">Nama Unit</label>

                        <div id="divider" class="margin-symmetric-vertical-6"></div>

                        <input type="name" class="form-control input-default"
                               id="warehouseInput" placeholder="Btl (Botol)"
                               value="{{ $unit->name }}" disabled>
                    </div>
                @endif

            </div>
        @else
            <h1>Data unit tidak ditemukan</h1>
        @endif
    </div>
</div>
