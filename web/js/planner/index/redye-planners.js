'use strict';

(function (){

// Функция изменения цвета заявки взависимости от статуса
    let redyeRequests = function () {

        let request = document.querySelectorAll('tr[data-key]');
        let requestSum = document.querySelector('div.summary');
        //let btnSucess = document.querySelectorAll('btn-success');
        let regexDate = formatDate(yesterday);
        let regexValue = {
            'postponed':/отложена/,
            'canceled':/отменена/,
            'wait':/ожидание/,
            'performed':/выполняется/,
            'completed':/завершена/
        };

        if (!!requestSum)requestSum.firstChild.nodeValue ='Показаны планы ';

        for (let i = 0; i < request.length; i++) {
        let text = request[i].innerText;
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

        let dd = date.getDate();
        if (dd < 10) dd = '0' + dd;

        let mm = date.getMonth() + 1;
        if (mm < 10) mm = '0' + mm;

        let yyyy = date.getFullYear();
        // if (yy < 10) yy = '0' + yy;

        return dd + '.' + mm + '.' + yyyy;
    }

    let today = new Date;
    let yesterday = new Date();
    yesterday.setDate(yesterday.getDate()-1);


//Диагностика
// let requestSum = document.querySelectorAll('div.summary');
// let th  = document.querySelectorAll('th[data-col-seq]');
// let filter = document.querySelectorAll('tr#w0-filters');
//     th[6].hidden=true;
//     filter[0].cells[8].hidden=true;
//     requestSum[0].firstChild.nodeValue='Показаны заявки ';
//     console.log(requestSum);


    let Diag = function() {
        let span = $('.my-tooltip');
        // p[1].addEventListener('click',function(event){
        //     let target = event.target;
        //     if (target.tagName !== 'A')return;
        //     let btn = document.querySelectorAll('a.btn');
        //     toggleSelect(target);
        //     //alert(target.innerHTML);
        //     console.log(target,btn);
        //  });

        // function toggleSelect(li) {
        //     li.className='btn-current';
        // }
        // btn = document.querySelectorAll('a.btn');
        console.log(span);
    };

    let myTooltip = function(){
        let myTooltip = $('.my-tooltip');
        for (let i = 0; i < myTooltip.length; i++) {
            let value = myTooltip[i].attributes[2].value;
            loadRequest(myTooltip, value, i);
        }
    };

    let loadRequest = function (item,id,i) {
        $.post(
            '/requests/lists?id='+id+'&flag=info&i='+i,
                function (data){
                    item[data[1]].attributes[2].value = data[0];
                }
         );
    };

    let copyModal = function(){
        $('[id^=copy]').on('click',function(event){
                event.preventDefault();
                let target = this.id;
                let myModal = $('#myModal');
                let modalBody = myModal.find('.modal-body');
                let modalTitle = myModal.find('.modal-header');
                // let formModal = myModal.querySelectorAll('form');
                let form = $('#w1');
                let action = form.attr('action');
                if (action){
                    form.attr('action',target);
                    console.log(action)
                }else{
                    form=$('#w2');
                    action = form.attr('action');
                    form.attr('action',target);
                    console.log(action)
                }

                //modalTitle.find('h2').html('Информация.');
                //modalBody.html('Тут будет информация.');
                // console.log(formModal);
                console.log(myModal);
                console.log(target);
                myModal.modal('show');
            }
        );
    };

    let changeYear = function () {
       $('#plannerfilter-month').on('change',function () {
         filterMonth();
         })
    };

    let filterMonth = function () {
        $('#plannerfilter-month').on('change',function () {
           alert('Привет');
        })
    } ;

    $(document).ready([redyeRequests,copyModal,myTooltip]);
    $(document).on('pjax:complete',redyeRequests);
    $(document).on('pjax:complete',copyModal);
    $(document).on('pjax:complete',myTooltip);  
    $(document).ready(Diag);
    //$(document).on('pjax:complete',Diag);


})();