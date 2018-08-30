'use strict';

//Обновления странице "заявки в работе" приложения по времени
$(document).ready(
    (function() {
    document.querySelector('#refreshButton');
    setInterval(function(){ $('#refreshButton').click();}, 15000); //Устанавливается время обновления
    })()
);
