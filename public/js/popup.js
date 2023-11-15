class Popup {

    static confirm(title, text, icon, confirmButton, cancelButton, onConfirm, onCancel) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: confirmButton,
            cancelButtonText: cancelButton,
        }).then((result) => {
            if (result.isConfirmed) {
                let onConfirmFunction = eval('(' + onConfirm + ')');
                onConfirmFunction();
                return;
            }

            let onCancelFunction = eval('(' + onCancel + ')');
            onCancelFunction();
        });
    }


}
