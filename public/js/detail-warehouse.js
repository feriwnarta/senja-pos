// saat modal detail item rack terbuka

document.addEventListener("after-load-modal-detail-item", (event) => {


    let rackId = event.detail.rackId;

    onScrollModalDetailItem(rackId);


});


document.addEventListener("after-load-modal-edit-item", (event) => {

    $(function () {

        let rackId = event.detail.rackId;
        onScrollModalEditItem(rackId);

        $('.item-modal input[type="checkbox"]').on('change', function () {
            console.log('change');
            // Cek apakah checkbox dicentang atau tidak
            if ($(this).prop('checked')) {
                let id = $(this).attr('id');
                console.log($(this).attr('id') + ' dicentang');
                Livewire.dispatch('item-added', {rackId: rackId, id: id, value: true});
            } else {
                let id = $(this).attr('id');
                console.log($(this).attr('id') + ' tidak dicentang');
                Livewire.dispatch('item-added', {rackId: rackId, id: id, value: false});
            }

            let modalItems = $('.item-modal');

            if (modalItems.length > 0) {
                let checkedCheckboxes = modalItems.find('input[type="checkbox"]:checked');

                // Membuat array untuk menyimpan nilai atribut data-meta yang mengandung nama item checkbox yang tercentang
                let item = checkedCheckboxes.map(function () {
                    return $(this).data('meta');
                }).get();

                Livewire.dispatch('update-item-rack', {rackId: rackId, item: item});

            } else {
                console.log('Elemen dengan kelas item-modal tidak ditemukan.');
            }
        });


    });

});

const onScrollModalEditItem = (rackId) => {
    let modalBody = $('#modalEditItem .modal-body');

    let isStop = false;

    // Infinity loading
    modalBody.on("scroll", function () {
        let scrollTop = modalBody.scrollTop();
        let scrollHeight = modalBody.prop("scrollHeight");
        let clientHeight = modalBody.prop("clientHeight");


        // Deteksi scroll layar di paling bawah
        if (scrollTop + clientHeight >= scrollHeight - 1 && !isStop) {
            console.log('Mencapai bagian bawah modal');
            // Lakukan aksi atau logika lainnya saat scroll mencapai bagian bawah modal
            Livewire.dispatch('load-more-edit', {rackId: rackId});
        }
    });


    Livewire.on('stop-request-edit', (event) => {
        console.log('asd');
        isStop = true;
    });
}


const onScrollModalDetailItem = (rackId) => {
    let modalBody = $('#modalDetailItem .modal-body');


    let isStop = false;

    // Infinity loading
    modalBody.on("scroll", function () {
        let scrollTop = modalBody.scrollTop();
        let scrollHeight = modalBody.prop("scrollHeight");
        let clientHeight = modalBody.prop("clientHeight");


        // Deteksi scroll layar di paling bawah
        if (scrollTop + clientHeight >= scrollHeight - 1) {
            console.log('Mencapai bagian bawah modal');
            // Lakukan aksi atau logika lainnya saat scroll mencapai bagian bawah modal
            Livewire.dispatch('load-more', {rackId: rackId});
        }
    });


    Livewire.on('stop-request', (event) => {
        isStop = true;
    });
}




