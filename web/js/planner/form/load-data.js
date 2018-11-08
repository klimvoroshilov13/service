'use strict';
(function () {
    var formCustomer = document.querySelector('div.field-planner-name_customers');
    var formContract =  document.querySelector('div.field-planner-info_contract');
    var formRequest =  document.querySelector('div.field-planner-info_request');
    window.selectCustomers =  document.querySelector('select#planner-name_customers');
    window.selectRequests =  document.querySelector('select#planner-info_request');

    window.pathContracts = {
        'url':'/contracts/lists?id=',
        'select':'select#planner-info_contract'
    };
    window.pathRequests = {
        'url':'/requests/lists?id=',
        'select':'select#select#planner-info_request'
    };

    window.pathCustomers = {
        'url':'/customers/lists?id=',
        'select':'select#planner-name_customers'
    };

    window.load = function (selector,select,url,callback,id,flag) {
        callback = callback || function (){};
        id = id || selector.options[selector.selectedIndex].value;
        flag = flag||'';
        $.post(url+id+'&flag='+flag,
            function(data){
                $(select).html(data);
                callback();
            });
    };

    var loadRequests = function () {
            formCustomer.hidden = false;
            formContract.hidden = false;
            load(selectRequests,pathCustomers.select,pathRequests.url,loadContracts);
    };

    var loadContracts = function () {
            load(selectCustomers,pathContracts.select,pathContracts.url);
    };

    selectCustomers.addEventListener('change',loadContracts);
    selectRequests.addEventListener('change',loadRequests);

})();