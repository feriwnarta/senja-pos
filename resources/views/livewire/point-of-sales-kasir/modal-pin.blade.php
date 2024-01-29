<div class="modal fade" id="modalOtorisasiPin" tabindex="-1" aria-labelledby="modalOtorisasiPinLabel" aria-hidden="true"
    data-bs-backdrop="true">
    <div class="modal-dialog modal-pin-wrapper modal-sm">
        <div class="modal-content">
            <div class="modal-header-pin">
                <h1 class="text-medium-20 color-4040 text-center" id="exampleModalToggleLabel">Otorisasi PIN</h1>
                <button class="color-4040" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body modal-pin-body-wrapper">
                {{-- x-data="{ screenNumber: '' }"
                x-model="screenNumber"
                @click = " screenNumber = 1 " --}}
                <div class="pin-content">
                    <div class="input-screen-wrapper">
                        <input type="text" id="screen-input" class="input-pin" min="0" readonly>
                        {{-- <p class="border-black border-1">{{ request()->input('screen-input') }}</p> --}}
                    </div>
                    <div class="button-pin-wrapper">
                        <div class="row row-button-wrapper">
                            <div class="button-number-wrapper">
                                <button onclick="addValue(1)" class="button-w70-eded p-6-16 text-medium-14">1</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(2)" class="button-w70-eded p-6-16 text-medium-14">2</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(3)" class="button-w70-eded p-6-16 text-medium-14">3</button>
                            </div>
                        </div>
                        <div class="row row-button-wrapper">
                            <div class="button-number-wrapper">
                                <button onclick="addValue(4)" class="button-w70-eded p-6-16 text-medium-14">4</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(5)" class="button-w70-eded p-6-16 text-medium-14">5</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(6)" class="button-w70-eded p-6-16 text-medium-14">6</button>
                            </div>
                        </div>
                        <div class="row row-button-wrapper">
                            <div class="button-number-wrapper">
                                <button onclick="addValue(7)" class="button-w70-eded p-6-16 text-medium-14">7</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(8)" class="button-w70-eded p-6-16 text-medium-14">8</button>
                            </div>
                            <div class="button-number-wrapper">
                                <button onclick="addValue(9)" class="button-w70-eded p-6-16 text-medium-14">9</button>
                            </div>
                        </div>
                        <div class="row row-button-wrapper">
                            <div class="button-position">
                                <div class="button-number-wrapper">
                                    <button onclick="addValue(0)"
                                        class="button-w70-eded p-6-16 text-medium-14">0</button>
                                </div>
                                <div class="button-number-wrapper">
                                    <button class="backspace-icon" onclick="clearDisplay()"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="button-f166-inh text-medium-16 p-8-16 text-white">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
