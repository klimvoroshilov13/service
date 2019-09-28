'use strict';

(function (){

// Функция изменения цвета заявки взависимости от статуса
let redyeRequests = function () {

    let request = document.querySelectorAll('tr[data-key]');
    let requestSum = document.querySelectorAll('div.summary');
    let regexDate = formatDate(today);
    let regexValue = {
        'postponed':/отложена/,
        'canceled':/отменена/,
        'wait':/ожидание/,
        'performed':/выполняется/,
        'completed':/завершена/
    };

    requestSum[0].firstChild.nodeValue='Показаны заявки ';

    for (let i = 0; i < request.length; i++) {
    let text = request[i].innerText;
    text.search(regexValue['wait']) > 0 ?
        request[i].className ='wait':null;
    text.search(regexValue['postponed']) > 0 ?
        request[i].className ='postponed':null;
    text.search(regexValue['performed']) > 0 ?
        request[i].className ='performed':null;
    text.search(regexDate) > 0 ?
        request[i].classList.add('today'):null;
    text.search(regexValue['completed']) > 0 ?
        request[i].className='':null;
    text.search(regexValue['completed']) > 0 || text.search(regexValue['postponed']) > 0 || text.search(regexValue['canceled']) > 0 || text.search(regexValue['performed']) > 0 ?
        request[i].classList.remove('today'):null;
    }
};

function formatDate(date) {

    let dd = date.getDate();
    if (dd < 10) dd = '0' + dd;

    let mm = date.getMonth() + 1;
    if (mm < 10) mm = '0' + mm;

    let yyyy = date.getFullYear();
    // if (yy < 10) yy = '0' + yy;

    return dd + '.' + mm + '.' + yyyy;
}

let today = new Date;

$(document).ready(redyeRequests);
$(document).on('pjax:complete',redyeRequests);

//Диагностика
// let requestSum = document.querySelectorAll('div.summary');
// let th  = document.querySelectorAll('th[data-col-seq]');
// let filter = document.querySelectorAll('tr#w0-filters');
//     th[6].hidden=true;
//     filter[0].cells[8].hidden=true;
//     requestSum[0].firstChild.nodeValue='Показаны заявки ';
//     console.log(requestSum);
// console.log(filter);
})();