var display = document.querySelector("#screen-input");

addValue = (valueInput) => {
    if (display.value.length >= 6) {
        return false;
    }

    display.value += valueInput;
};

clearDisplay = () => {
    var displayed = display.value;
    document.querySelector("#screen-input").value = displayed.substr(
        0,
        displayed.length - 1
    );
};
