<div class="modal-dialog modal-wrapper">
    <div class="modal-content">
        <div
            class="modal-header header-body-wrapper d-flex flex-row justify-content-center align-items-center sticky-top">
            <h1 class="jumlah-pelanggan">Pilih Meja</h1>
        </div>
        <div class="modal-body modal-body-wrapper">
            <div class="select-meja-wrapper">
                @php
                    $radioCategory = ['Semua Meja', 'Kosong', 'Di Tempati', 'Di Pesan'];
                @endphp
                <div class="radio-group-wrapper">
                    @foreach ($radioCategory as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio{{ $category }}"
                                value="{{ $category }}">
                            <label class="form-check-label" for="inlineRadio{{ $category }}">{{ $category }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="select-meja">
                </div>
            </div>
        </div>
    </div>
</div>
