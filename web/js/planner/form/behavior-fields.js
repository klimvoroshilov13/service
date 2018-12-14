'use strict';
(function () {
    var formJobs = document.querySelector('select#planner-name_jobs');
    var formRequest = document.querySelector('div.field-planner-info_request');
    var formCustomer = document.querySelector('div.field-planner-name_customers');
    var formContract = document.querySelector('div.field-planner-info_contract');
    var selectedVal = document.querySelectorAll('option[selected]');
    var selectCustomers = document.querySelector('select#planner-name_customers');
    var selectRequests = document.querySelector('select#planner-info_request');

    var pathContracts = {
        'url':'/contracts/lists?id=',
        'select':'select#planner-info_contract'
    };
    var pathRequests = {
        'url':'/requests/lists?id=',
        'select':'select#planner-info_request'
    };

    var pathCustomers = {
        'url':'/customers/lists?id=',
        'select':'select#planner-name_customers'
    };

    // Функция загрузки данных методом post
    var load = function (selector,select,url,callback,id,flag) {
        callback = callback || function (){};
        id = id || selector.options[selector.selectedIndex].value;
        //console.log(id);
        flag = flag || '';
        $.post(url+id+'&flag='+flag,
            function(data){
                $(select).html(data);
                callback();
                //console.log(data);
        });
    };

    //Функция загрузки данных для значения "заявка"
    var loadRequests = function () {
        formCustomer.hidden = false;
        formContract.hidden = false;
        load(selectRequests,pathCustomers.select,pathRequests.url,loadContracts);
    };

    //Функция загрузки данных для значения "контрагенты"
    var loadContracts = function () {
        load(selectCustomers,pathContracts.select,pathContracts.url);
    };

    //Функция сценария поведения 2-го исполнителя
    var behaviorPerformer = function () {
       var formInput = document.querySelector('input#planner-name_performers2');
       var formDiv = document.querySelectorAll('div.field-planner-name_performers2');
       selectedVal[0].value === 'ожидание' ? formDiv[1].hidden = true: formDiv[1].hidden = false;
       formInput.addEventListener('click',function () {
           formInput.checked ? formDiv[1].hidden = false :formDiv[1].hidden = true;
       });
    };

    //Функция сценария поведения поля наименование работ
    var behaviorJobs = function () {
       var val = selectedVal[0].value;
       val==='заявка'? formRequest.hidden = false:formRequest.hidden = true;
       formJobs.addEventListener('change',function () {
           var val = formJobs.options[formJobs.selectedIndex].value;
           if (val==='заявка'){
               formRequest.hidden = false;
               formCustomer.hidden = true;
               formContract.hidden = true;
               load(selectRequests,pathRequests.select,pathRequests.url,'','','all');
           }else{
               formRequest.hidden = true;
               formCustomer.hidden = false;
               formContract.hidden = false;
               load(selectCustomers,pathCustomers.select,pathCustomers.url,'','1','all');
               $('select#planner-info_contract').html("<option>Выберите договор...</option>");
               $('select#planner-info_request').html("<option></option>");
           }
        });
   };

   selectCustomers.addEventListener('change',loadContracts);
   selectRequests.addEventListener('change',loadRequests);
   $(document).ready([behaviorPerformer,behaviorJobs]);
   $(document).on('pjax:complete',behaviorPerformer);
   $(document).on('pjax:complete',behaviorJobs);
   //console.log(selectedVal);
})();