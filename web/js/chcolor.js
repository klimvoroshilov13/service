'use strict';

// Функция  обновления странице "заявки в работе" приложения по времени
var refreshDocument = function() {
    var refreshButton = document.querySelector('#refreshButton');
    setInterval(function(){ $('#refreshButton').click();}, 15000); //Устанавливается время обновления
};

// Функция изменения цвета заявки взависимости от статуса
var changeColor = function () {

    var request = document.querySelectorAll('tr[data-key]');
    // var regexDate = date;
    var regexPostponed = /отложена/;
    var regexWait = /ожидание/;
    var regexPerformed = /выполняется/;

    for (var i = 0; i < request.length; i++) {
    var text = request[i].innerText;
    text.search(regexPostponed) > 0 ? request[i].className='postponed':null;
    text.search(regexWait) > 0 ? request[i].className='wait':null;
    text.search(regexPerformed) > 0 ? request[i].className='performed':null;
    // text.search(regexDate) > 0 ? request[i].className='today':null;
    }
};

// function formatDate(date) {
//
//     var dd = date.getDate();
//     if (dd < 10) dd = '0' + dd;
//
//     var mm = date.getMonth() + 1;
//     if (mm < 10) mm = '0' + mm;
//
//     var yyyy = date.getFullYear();
//     // if (yy < 10) yy = '0' + yy;
//
//     return dd + '.' + mm + '.' + yyyy;
// }
//
// var today = new Date;

$(document).ready([refreshDocument,changeColor]);
$(document).on('pjax:complete',changeColor);

//Диагностика
// var request = document.querySelectorAll('tr[data-key]');
// console.log(formatDate(today));