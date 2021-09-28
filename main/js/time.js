let date = new Date();
let time = date.getTime();
let jam = date.getHours();
console.log(jam);
if( jam > 5 && jam < 11  ) {
    var sapa = "selamat pagi";
} else if( jam >= 11 && jam < 15 ) {
    var sapa = "selamat siang";
} else if( jam >= 16 && jam < 19 ) {
    var sapa = "selamat sore";
} else {
    var sapa = "selamat malam";
}

let h2 = document.querySelector('h2');
h2.innerHTML = sapa;