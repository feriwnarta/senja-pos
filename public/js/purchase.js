window.onload = setPaymentDate;

document.addEventListener('livewire:navigated', () => {
    setPaymentDate();
})
document.getElementById('paymentDateOption').addEventListener('change', setPaymentDate);
document.getElementById('datePayment').addEventListener('change', calculateDaysDifference);

function calculateDaysDifference() {
    const datePaymentInput = document.getElementById('datePayment');
    const durationSelect = document.getElementById('paymentDateOption');

    if (datePaymentInput.value) {
        const selectedDate = new Date(datePaymentInput.value);
        const todayDate = new Date();
        let daysDifference = (selectedDate - todayDate) / (24 * 60 * 60 * 1000);

        // Determine whether the selected date is greater or less than today's date
        if (selectedDate > todayDate) {
            daysDifference = Math.ceil(daysDifference);
        } else {
            daysDifference = Math.floor(daysDifference);
        }

        if (!isNaN(daysDifference)) {
            addOptionAndSelect(daysDifference);
        } else {
            alert('Invalid date format.');
        }
    }
}

function addOptionAndSelect(daysDifference) {
    const durationSelect = document.getElementById('paymentDateOption');
    const existingOption = Array.from(durationSelect.options).find(option => option.value === daysDifference.toString());

    if (!existingOption) {
        const newOption = document.createElement('option');
        newOption.value = daysDifference;
        newOption.text = `${daysDifference} Hari`;

        Livewire.dispatch('set-due-date', {date: daysDifference});

        // durationSelect.add(newOption);
        // durationSelect.options.selectedIndex = [...durationSelect.options].findIndex(option => option.value === daysDifference.toString());
    } else {
        durationSelect.value = daysDifference;
    }
}

function setPaymentDate() {
    const durationSelect = document.getElementById('paymentDateOption');
    const datePaymentInput = document.getElementById('datePayment');
    const selectedDuration = parseInt(durationSelect.value, 10);
    const todayDate = new Date();


    // Determine whether the selected duration is positive or negative
    if (selectedDuration >= 0) {
        todayDate.setDate(todayDate.getDate() + selectedDuration);
    } else {
        todayDate.setDate(todayDate.getDate() - Math.abs(selectedDuration));
    }

    const formattedDate = todayDate.toISOString().split('T')[0];
    datePaymentInput.value = formattedDate;

    const existingOption = Array.from(durationSelect.options).find(option => option.value === selectedDuration.toString());

    if (!existingOption) {
        const newOption = document.createElement('option');
        newOption.value = selectedDuration;
        newOption.text = `${selectedDuration} Days`;
        durationSelect.add(newOption);
        durationSelect.options.selectedIndex = [...durationSelect.options].findIndex(option => option.value === selectedDuration.toString());
    }
}
