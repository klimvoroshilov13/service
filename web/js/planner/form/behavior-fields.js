'use strict';
(function () {
    let formJobs = document.querySelector('select#planner-name_jobs');
    let formRequest = document.querySelector('div.field-planner-info_request');
    let formCustomer = document.querySelector('div.field-planner-customer_id');
    let formContract = document.querySelector('div.field-planner-info_contract');
    let selectedVal = document.querySelectorAll('option[selected]');
    let selectCustomers = document.querySelector('select#planner-customer_id');
    let selectRequests = document.querySelector('select#planner-info_request');

    let pathContracts = {
        'url':'/contracts/lists?id=',
        'select':'select#planner-info_contract'
    };
    let pathRequests = {
        'url':'/requests/lists?id=',
        'select':'select#planner-info_request'
    };

    let pathCustomers = {
        'url':'/customers/lists?id=',
        'select':'select#planner-customer_id'
    };

    // Функция загрузки данных методом post
    let load = function (selector,select,url,callback,id,flag) {
        callback = callback || function (){};
        id = id || selector.options[selector.selectedIndex].value;
        // console.log([id,selector]);
        flag = flag || '';
        $.post(url+id+'&flag='+flag,
            function(data){
                $(select).html(data);
                callback();
                //console.log(data);
        });
    };

    // Функция загрузки данных для значения "заявка"
    let loadRequests = function () {
        formCustomer.hidden = false;
        formContract.hidden = false;
        load(selectRequests,pathCustomers.select,pathRequests.url,loadContracts);
    };

    // Функция загрузки данных для значения "контрагенты"
    let loadContracts = function () {
        load(selectCustomers,pathContracts.select,pathContracts.url);
    };

    // Функция сценария поведения 2-го исполнителя
    let behaviorPerformer = function () {
       let formInput = document.querySelector('input#planner-name_performers2');
       let formDiv = document.querySelectorAll('div.field-planner-name_performers2');
       selectedVal[0].value === 'ожидание' ? formDiv[1].hidden = true: formDiv[1].hidden = false;
       if (selectedVal.length === 6 ) {
           selectedVal[4].value ? formInput.setAttribute('checked','checked'): null;
       }
       formInput.checked ? formDiv[1].hidden = false :formDiv[1].hidden = true;
       formInput.addEventListener('click',function () {
           formInput.checked ? formDiv[1].hidden = false :formDiv[1].hidden = true;
       });
        // console.log(formDiv);
    };

    // Функция сценария поведения поля наименование работ
    let behaviorJobs = function () {
       let val = selectedVal[0].value;
       val==='заявка'? formRequest.hidden = false:formRequest.hidden = true;
       formJobs.addEventListener('change',function () {
           let val = formJobs.options[formJobs.selectedIndex].value;
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

   // Поведение поля конракты при редактирование планов
    let updateContract = function () {
        if (selectedVal[1]) {
            let val = selectedVal[1].value;
            load(selectCustomers,pathContracts.select,pathContracts.url,'',val);
        }
        console.log(val);
    };


   selectCustomers.addEventListener('change',loadContracts);
   selectRequests.addEventListener('change',loadRequests);
   $(document).ready([behaviorPerformer,behaviorJobs]);
   $(document).on('pjax:complete',behaviorPerformer);
   $(document).on('pjax:complete',behaviorJobs);
   console.log(selectedVal);

})();
