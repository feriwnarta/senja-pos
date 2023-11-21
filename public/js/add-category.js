$(() => {
    const onScrollModalEditItem = () => {
        let modalBody = $('#modalAddCategory .modal-body');

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
                Livewire.dispatch('load-more');
            }
        });


        Livewire.on('stop-request', (event) => {

            isStop = true;
        });
    }


    onScrollModalEditItem();

})

function closeDropdown() {
    $('.dropdown-toggle').dropdown('toggle');
}














