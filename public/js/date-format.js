// let newDate = document.getElementById("date");
const formatDate = () => {
    var todayDate = new Date().toISOString().slice(0, 10).split("-");

    var splitDate = todayDate;

    var year = splitDate[2];
    var month = splitDate[1];
    var day = splitDate[0];

    var newFormatDate = `${year}/${month}/${day}`;

    return newFormatDate;
};
