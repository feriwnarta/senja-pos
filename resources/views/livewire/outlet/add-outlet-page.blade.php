<div id="content-loaded">
    <x-notify::notify/>
    <div class="row">
        <div class="col-sm-4 offset-1">
            {{-- KODE OUTLET --}}
            <div class="container-input-default">
                <label for="warehouseInput"
                       class="form-label input-label">Kode outlet</label>

                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <input type="name" class="form-control input-default"
                       id="warehouseInput" placeholder="OCS002"
                       wire:model.live.debounce.600ms="code">
                @error('code') <span class="error">{{ $message }}</span> @enderror

            </div>

            {{-- NAMA OUTLET --}}
            <div class="container-input-default margin-top-24">
                <label for="warehouseInput"
                       class="form-label input-label">Nama outlet</label>

                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <input type="name" class="form-control input-default"
                       id="warehouseInput" placeholder="Cahaya Senja Bandung"
                       wire:model.live.debounce.600ms="name">
                @error('name') <span class="error">{{ $message }}</span> @enderror

            </div>

            {{-- ALAMAT OUTLET --}}
            <div class="container-input-default margin-top-24">
                <label for="warehouseInput"
                       class="form-label input-label">Alamat outlet</label>

                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <input type="name" class="form-control input-default"
                       id="warehouseInput" placeholder="Jl Braga Bandung Asia Afrika"
                       wire:model.live.debounce.600ms="address">
                @error('address') <span class="error">{{ $message }}</span> @enderror

            </div>

            {{-- NOMOR TELPON OUTLET --}}
            <div class="container-input-default margin-top-24">
                <label for="warehouseInput"
                       class="form-label input-label">Nomor telpon outlet</label>

                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <input type="number" class="form-control input-default"
                       id="warehouseInput" placeholder="08xxxxxxxxx"
                       wire:model.live.debounce.600ms="phone">
                @error('phone') <span class="error">{{ $message }}</span> @enderror

            </div>

            {{-- EMAIL OUTLET --}}
            <div class="container-input-default margin-top-24">
                <label for="warehouseInput"
                       class="form-label input-label">Email outlet</label>

                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <input type="email" class="form-control input-default"
                       id="warehouseInput" placeholder="cahayasenja.bandung@gmail.com"
                       wire:model.live.debounce.600ms="email">
                @error('email') <span class="error">{{ $message }}</span> @enderror

            </div>


            {{-- RESUPPLY OUTLET --}}
            <div class="container-input-default margin-top-24">
                <label for="resupplyOutlet" class="form-label input-label">Re-supply dari</label>
                <div id="divider" class="margin-symmetric-vertical-6"></div>

                <select class="form-select input-default" id="resupplyOutlet" wire:model="selectedCentralKitchen">
                    <option value="" disabled selected>Pilih central kitchen</option>
                    @foreach($centralKitchens as $centralKitchen)
                        <option value="{{ $centralKitchen->id }}">{{ $centralKitchen->name }}</option>
                    @endforeach
                </select>

                @error('selectedCentralKitchen') <span class="error">{{ $message }}</span> @enderror
            </div>


        </div>
    </div>
</div>


