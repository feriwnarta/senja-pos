let areaHtml = `     <tr>
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
                                <button class="btn icon-text" type="button" id="addItem" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#modalItem" onclick="dynamicTitleModalAddWarehouse(this)" @click="$dispatch('load-item')">
                                    + Item
                                </button>
                            </td>
                            <td class="delete-item">
                                <i class="x-icon"></i>
                            </td>
                        </tr>`;

let rackHtml = ` <tr>
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
let modalItemHtml = '';

$(function () {

});

document.addEventListener('load-add-warehouse-script', function () {
    // sembunyikan button tambah rak
    hideBtnAddRack();

    // inisialisasi area
    addArea();

    // inisialisasi rak
    addRack();

    // simpan title modal awal
    initTitle = $('.modal-title').html();
});


let nextPageUrl = null;
let isLoading = false; // Tambahkan variabel untuk melacak status loading

function getListItem() {
    if (isLoading) return; // Hentikan permintaan jika masih dalam proses loading
    isLoading = true;

    $.ajax({
        url: (nextPageUrl == null) ? "/warehouse/list-item" : nextPageUrl,
        method: "GET",
        success: function (response) {
            // set data ke body modal

            response.data.forEach(function (item) {
                modalItemHtml += `<h1>${item.name}</h1>`
            });

            nextPageUrl = response.next_page_url;

            if (nextPageUrl == null) {
                return;
            }

            $('.modal-body').html(modalItemHtml);

            isLoading = false; // Setelah data dimuat, atur isLoading menjadi false
            infiniteLoadingPagination();
        },
    })
}

function infiniteLoadingPagination() {
    $("#modalItem").on("shown.bs.modal", function () {
        $(this)
            .find(".modal-body")
            .off("scroll") // Matikan event scroll sebelum menghubungkan lagi
            .scroll(function () {
                var scrollTop = $(this).scrollTop();
                var scrollHeight = $(this).prop("scrollHeight");
                var clientHeight = $(this).prop("clientHeight");

                if (scrollTop + clientHeight + 1 >= scrollHeight) {
                    getListItem();
                }
            });
    });
}

function dynamicTitleModalAddWarehouse(obj) {


    const areaInput = getValueInputClosest('.areaInput', obj, 'tr');
    const rackInput = getValueInputClosest('.rackInput', obj, 'tr');
    const categoryInvInput = getValueInputClosest('.catInvInput', obj, 'tr');

    nextPageUrl = null;
    modalItemHtml = '';
    $('.modal-body').empty();

    // dapatkan list item
    getListItem();

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

