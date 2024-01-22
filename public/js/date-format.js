let newDate = document.getElementById("date");
todayDate = new Date().toISOString().slice(0, 10).split("-");

let splitDate = todayDate;

let year = splitDate[2];
let month = splitDate[1];
let day = splitDate[0];

let newFormatDate = `${year}/${month}/${day}`;
console.log(newFormatDate);
