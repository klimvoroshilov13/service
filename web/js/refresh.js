'use strict';
$(document).ready(function() {
    var refreshButton = document.querySelector('#refreshButton');
    setInterval(function(){ $('#refreshButton').click();}, 5000);
    refreshButton.addEventListener('click',function () {alert('ok!');
    });
    //setInterval(function(){ $('#refreshButton').click();}, 45000);
    //setInterval(function(){ alert('ok!');}, 5000);
});
