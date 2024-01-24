<div class="modal fade" id="modalPilihMeja" tabindex="-1" aria-labelledby="modalPilihMejaLabel" aria-hidden="true"
    data-bs-backdrop="true">
    <div class="modal-dialog modal-wrapper">
        <div class="modal-content">
            <div
                class="modal-header header-body-wrapper d-flex flex-row justify-content-center align-items-center sticky-top">
                <h1 class="text-medium-20 color-4040">Pilih Meja</h1>
            </div>
            <div class="modal-body modal-meja-wrapper">
                <div class="select-meja-wrapper">
                    @php
                        $radioCategory = ['Semua Meja', 'Kosong', 'Di Tempati', 'Di Pesan'];
                    @endphp
                    <div class="radio-group-wrapper">
                        @foreach ($radioCategory as $category)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                    id="inlineRadio{{ $category }}" value="{{ $category }}">
                                <label class="form-check-label text-light-14 color-4040"
                                    for="inlineRadio{{ $category }}">{{ $category }}</label>
                            </div>
                        @endforeach
                    </div>
                    @php
                        $noMeja = [12, 13, 14, 15, 16, 17, 18, 19];
                    @endphp
                    <div class="card-meja">
                        @foreach ($noMeja as $meja)
                            <button class="meja-wrapper">
                                <div class="dash-meja">
                                    <div class="meja">
                                        <span>T-{{ $meja }}</span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <div class="button-pilih-meja-group">
                        <button class="button-outline-w119-f166 text-medium-16 color-f166 p-8-16 ls-176 h-40"
                            data-bs-dismiss="modal">Keluar</button>
                        <button class="button-w119-f166 text-medium-16 text-white p-8-16 ls-176 h-40">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
