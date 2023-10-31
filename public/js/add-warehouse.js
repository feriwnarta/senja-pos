const areaHtml = `     <tr>
                            <td>
                                <input type="text" class="input-no-border make-input areaInput caption-medium"
                                       placeholder="Area A" style="width: 100%">
                            </td>
                            <td><input type="text" class="input-no-border make-input rackInput caption-medium"
                                       placeholder="A1"
                                       style="width: 100%"></td>
                            <td><input type="text" class="input-no-border make-input catInvInput caption-medium"
                                       placeholder="Bahan mentah" style="width: 100%"></td>
                            <td>
                                <button class="btn icon-text" type="button" id="addItem" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#modalItem" onclick="dynamicTitleModalAddWarehouse(this)">
                                    + Item
                                </button>
                            </td>
                        </tr>`;

const rackHtml = ` <tr>
                            <td>

                            </td>
                            <td><input type="text" class="input-no-border make-input rackInput caption-medium"
                                       placeholder="A1"
                                       style="width: 100%"></td>
                            <td><input type="text" class="input-no-border make-input catInvInput caption-medium"
                                       placeholder="Bahan mentah" style="width: 100%"></td>
                            <td>
                                <button class="btn icon-text" type="button" id="addItem" data-bs-toggle="modal" data-bs-target="#modalItem" onclick="dynamicTitleModalAddWarehouse(this)">
                                    + Item
                                </button>
                            </td>
                        </tr>`;


let initTitle = '';
let area = '';
$(function () {
    // sembunyikan button tambah rak
    hideBtnAddRack();

    // inisialisasi area
    addArea();

    // inisialisasi rak
    addRack();

    // simpan title modal awal
    initTitle = $('.modal-title').html();

});

function dynamicTitleModalAddWarehouse(obj) {


    const areaInput = getValueInputClosest('.areaInput', obj, 'tr');
    const rackInput = getValueInputClosest('.rackInput', obj, 'tr');
    const categoryInvInput = getValueInputClosest('.catInvInput', obj, 'tr');

    if (areaInput !== undefined) {
        area = areaInput;
    }

    if (areaInput !== undefined && rackInput !== undefined && categoryInvInput !== undefined) {
        $('.modal-title').html(`${initTitle}\t${areaInput}/${rackInput}/${categoryInvInput}`);
        return;
    }

    $('.modal-title').html(`${initTitle}\t${area}/${rackInput}/${categoryInvInput}`);

}

function getValueInputClosest(target, location, closest) {
    const parent = $(location).closest(closest);
    const areaInput = parent.find(target); // Menggunakan kelas sebagai contoh
    return areaInput.val();
}

// fungsi dapatkan value input
function getValueInput(selector) {
    return $(`${selector}`).val();
}


// hide button rack pertama
function hideBtnAddRack() {
    $('#addRack').hide();
}


// tampilkan button add rack
function showBtnAddRack() {
    $('#addRack').show();

}


/**
 * menambahkan row tambah area ke table area gudang
 */
function addArea() {
    $('#addArea').click(function () {

        // tampilkan rack
        showBtnAddRack();

        // tambahkan data input ke table
        addBeforeWarehouseAction(areaHtml);

    });
}


/**
 * menambahkan row tambah rak ke table area gudang
 */
function addRack() {
    $('#addRack').click(function () {
        addBeforeWarehouseAction(rackHtml);
    });
}

/**
 * sisipkan html sebelum button action + area dan + rak
 * @param html
 */
function addBeforeWarehouseAction(html) {
    $('#addWarehouseAction').before(html);
}

