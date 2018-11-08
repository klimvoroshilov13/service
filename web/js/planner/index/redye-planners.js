'use strict';

(function (){

// Функция изменения цвета заявки взависимости от статуса
var redyeRequests = function () {

    var request = document.querySelectorAll('tr[data-key]');
    var requestSum = document.querySelector('div.summary');
    //var btnSucess = document.querySelectorAll('btn-success');
    var regexDate = formatDate(yesterday);
    var regexValue = {
        'postponed':/отложена/,
        'canceled':/отменена/,
        'wait':/ожидание/,
        'performed':/выполняется/,
        'completed':/завершена/
    };

    if (!!requestSum)requestSum.firstChild.nodeValue ='Показаны планы ';

    for (var i = 0; i < request.length; i++) {
    var text = request[i].innerText;
    // text.search(regexValue['wait']) > 0 ?
    //     request[i].className ='wait':null;
    // text.search(regexValue['postponed']) > 0 ?
    //     request[i].className ='postponed':null;
    // text.search(regexValue['performed']) > 0 ?
    //     request[i].className ='performed':null;
    // text.search(regexDate) > 0 ?
    //     request[i].classList.add('today'):null;
    text.search(regexValue['completed']) > 0 ?
        request[i].className='completed':null;
    // text.search(regexValue['completed']) > 0 || text.search(regexValue['postponed']) > 0 || text.search(regexValue['canceled']) > 0 || text.search(regexValue['performed']) > 0 ?
    //     request[i].classList.remove('today'):null;
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
    var yesterday = new Date();
    yesterday.setDate(yesterday.getDate()-1);




//Диагностика
// var requestSum = document.querySelectorAll('div.summary');
// var th  = document.querySelectorAll('th[data-col-seq]');
// var filter = document.querySelectorAll('tr#w0-filters');
//     th[6].hidden=true;
//     filter[0].cells[8].hidden=true;
//     requestSum[0].firstChild.nodeValue='Показаны заявки ';
//     console.log(requestSum);


    var Diag = function(){
        var p = document.querySelectorAll('p');
        p[1].addEventListener('click',function(event){
            var target = event.target;
            if (target.tagName !== 'A')return;
            var btn = document.querySelectorAll('a.btn');
            toggleSelect(target);
            //alert(target.innerHTML);
            console.log(target,btn);
         });

         function toggleSelect(li) {
             li.className='btn-current';
         }
         // btn = document.querySelectorAll('a.btn');
         console.log(p);
         };

 $(document).ready(redyeRequests);
 // $(document).ready(Diag);
 $(document).on('pjax:complete',redyeRequests);
 //$(document).on('pjax:complete',Diag);
})();