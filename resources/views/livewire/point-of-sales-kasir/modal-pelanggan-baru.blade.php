<div wire:ignore.self class="modal fade" id="modalPelangganBaru" tabindex="-1" aria-labelledby="modalPelangganBaruLabel"
    data-bs-backdrop="true">
    <div class="modal-dialog modal-wrapper">
        <div class="modal-content">
            <div
                class="modal-header header-body-wrapper d-flex flex-row justify-content-center align-items-center sticky-top">
                <h1 class="text-medium-20 color-4040">Pelanggan Baru</h1>
            </div>
            <div class="modal-body modal-pelangganBaru-wrapper">
                <div class="body-input-pelanggan-wrapper">
                    <form class="input-pelanggan-baru d-flex flex-column gap-3" wire:submit="save">
                        <div class="d-flex flex-column gap-1">
                            <label class="text-light-14 color-4040">Nama</label>
                            <input class="form-control text-light-14 color-7575" type="text"
                                placeholder="Nama Pelanggan" aria-label="nama" wire:model.live="name">
                            <div>
                                @error('name')
                                    <small class="d-block text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label class="text-light-14 color-4040">Nomor Telfon</label>
                            <input class="form-control text-light-14 color-7575" type="number"
                                placeholder="Nomor Telepon" aria-label="noTelp" wire:model.live="phoneNumber">
                            <div>
                                @error('phoneNumber')
                                    <small class="d-block text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <label class="text-light-14 color-4040">Email</label>
                            <input class="form-control text-light-14 color-7575" type="email" placeholder="Email"
                                aria-label="nama" wire:model.live="emailAddress">
                            <div>
                                @error('emailAddress')
                                    <small class="d-block text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="button-group-new-customer">
                            <button type="button"
                                class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 ls-176 h-40"
                                data-bs-dismiss="modal">Keluar</button>
                            <button class="button-w119-f166 text-medium-16 text-white p-8-16 ls-176 h-40"
                                data-bs-toggle="modal" data-bs-target="#modalPilihPelanggan">Simpan</button>
                            {{-- data-bs-toggle="modal" data-bs-target="#modalPilihPelanggan" --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
