'use strict';
(function () {
    var hidePerformer=function () {
       var formInput = document.querySelector('input#planner-name_performers2');
       var formDiv = document.querySelectorAll('div.field-planner-name_performers2');
       formDiv[1].hidden=true;
       formInput.addEventListener('click',function () {
           formInput.checked ? formDiv[1].hidden=false :formDiv[1].hidden=true;
       });
   };
   var hideRequest=function () {
       var formJobs = document.querySelector('select#planner-name_jobs');
       var selectedVal = document.querySelectorAll('option[selected]');
       var formRequest = document.querySelector('div.field-planner-info_request');
       var formCustomer = document.querySelector('div.field-planner-name_customers');
       var formContract = document.querySelector('div.field-planner-info_contract');
       var val = selectedVal[0].value;
       val==='заявка'? formRequest.hidden = false:formRequest.hidden = true;
       formJobs.addEventListener('change',function () {
           var val = formJobs.options[formJobs.selectedIndex].value;
           if (val==='заявка'){
               formRequest.hidden = false;
               formCustomer.hidden = true;
               formContract.hidden = true;
               $.post('/requests/lists?id=1&flag=all',function(data){$('select#planner-info_request').html(data);});
           }else{
           formRequest.hidden = true;
           formCustomer.hidden = false;
           formContract.hidden = false;
            $.post('/customers/lists?id=1',function(data){$('select#planner-name_customers').html(data);});
            $('select#planner-info_contract').html("<option>Выберите договор...</option>");
            $('select#planner-info_request').html("<option></option>");
           }
        });
   };
   $(document).ready([hidePerformer,hideRequest]);
    console.log(document.querySelectorAll('option[selected]'));
})();