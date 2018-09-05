'use strict';

// Функция изменения цвета заявки взависимости от статуса
var redyeRequests = function () {

    var request = document.querySelectorAll('tr[data-key]');
    var regexDate = formatDate(today);
    var regexPostponed = /отложена/;
    var regexWait = /ожидание/;
    var regexPerformed = /выполняется/;
    var regexCompleted = /завершена/;

    for (var i = 0; i < request.length; i++) {
    var text = request[i].innerText;
    text.search(regexPostponed) > 0 ?
        request[i].classList.add('postponed'):null;
    text.search(regexWait) > 0 ?
        request[i].classList.add('wait'):null;
    text.search(regexPerformed) > 0 ?
        request[i].classList.add('performed'):null;
    text.search(regexDate) > 0 ?
        request[i].classList.add('today'):null;
    text.search(regexCompleted) > 0 || text.search(regexPostponed) > 0 || text.search(regexPerformed) > 0 ?
        request[i].classList.remove('today'):null;
    }
};

function formatDate(date) {

    var dd = date.getDate();
    if (dd < 10) dd = '0' + dd;

    var mm = date.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;

    var yyyy = date.getFullYear();
    // if (yy < 10) yy = '0' + yy;

    return dd + '.' + mm + '.' + yyyy;
}

var today = new Date;

$(document).ready(redyeRequests);
$(document).on('pjax:complete',redyeRequests);

//Диагностика
// var request = document.querySelectorAll('tr[data-key]');
// console.log(request);