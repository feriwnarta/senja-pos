$(() => {


    $("#modalItem").on("shown.bs.modal", function () {
        let modalBody = $(this).find(".modal-body");
        let isStop = false;


        // infinity loading
        modalBody.on("scroll", function () {
            let scrollTop = modalBody.scrollTop();
            let scrollHeight = modalBody.prop("scrollHeight");
            let clientHeight = modalBody.prop("clientHeight");

            // deteksi scroll layar dipaling bawah
            if (scrollTop + clientHeight + 1 >= scrollHeight && !isStop) {


                // trigger event untuk meload data lebih banyak
                Livewire.dispatch('load-more');
            }

        });

        Livewire.on('stop-request', (event) => {
            isStop = true;
        });


    });

    // deteksi modal ditutup
    $("#modalItem").on("hidden.bs.modal", function () {
        // kirim event ke livewire untuk menutup modal
        Livewire.dispatch('dismiss-modal');
    });


    // deteksi modal item baru ditutup
    $("#modalNewItem").on("hidden.bs.modal", function () {
        // kirim event ke livewire untuk menutup modal
        Livewire.dispatch('dismiss-modal-new-item');

    });


    Livewire.on('reject-checkbox', (event) => {
        // tampilkan pesan tidak boleh tambahkan item lebih dari satu area atau rak
        alert('hanya diperbolehkan satu item di satu area / rack, item ini tidak akan ditambahkan');

        // batalkan item yang dicheck
        $('.item-modal .name-item-modal:contains(' + event + ')').each(function () {
            // Melakukan sesuatu dengan elemen yang ditemukan, misalnya, mencetak teksnya
            $(this).closest('.data-modal').find('#itemCheckBox').prop('checked', false);
        });
    });


});
