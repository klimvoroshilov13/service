'use strict';
// Находим все заявки
// Находим заявки со стусом "ожидание" и изменяем класс строки на wait.
var request = document.querySelectorAll('tr[data-key]');
var regexPostponed = /отложена/;
var regexWait = /ожидание/;
var regexPerformed = /выполняется/;
for (var i = 0; i < request.length; i++) {
var text = request[i].innerText;
text.search(regexPostponed) > 0 ? request[i].className='postponed':null;
text.search(regexWait) > 0 ? request[i].className='wait':null;
text.search(regexPerformed) > 0 ? request[i].className='performed':null;
};

//console.log(request );

