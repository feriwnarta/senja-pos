let newDate = document.getElementById("date").value;
todayDate = new Date().toISOString().slice(0, 10).split("-");

console.log(`Before ${todayDate}`);

let splitDate = todayDate;

let year = splitDate[2];
let month = splitDate[1];
let day = splitDate[0];

let newFormatDate = `${day}-${month}-${year}`;
console.log(`After ${newFormatDate}`);
