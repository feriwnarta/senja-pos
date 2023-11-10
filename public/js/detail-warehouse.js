// saat modal detail item rack terbuka

document.addEventListener("after-load-modal-detail-item", (event) => {


    let rackId = event.detail.rackId;

    onScrollModalDetailItem(rackId);


});


document.addEventListener("after-load-modal-edit-item", (event) => {
    console.log('asd');
});


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




