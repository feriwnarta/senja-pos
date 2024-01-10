let triggerCheckbox = document.querySelectorAll('input[type="checkbox"]')

function checkAll(checkboxes) {
    // Memberikan kondisi jika checkbox is checked
    if (checkboxes.checked === true) {
        // Mengecek variable triggerCheckbox
        triggerCheckbox.forEach((checkbox)=>{
            // Memberitahu sistem bahwa triggerCheckbox yang dimasukkan kedalam variable checkbox bernilai true
            checkbox.checked = true;
        });
    } else {
        triggerCheckbox.forEach((checkbox)=>{
            checkbox.checked = false;
        });
    }
}
