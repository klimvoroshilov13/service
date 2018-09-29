'use strict';
(function () {
    var loadData = function () {
      window.selectCustomers =  document.querySelector('select#planner-name_customers');
      var selectRequests =  document.querySelector('select#planner-info_request');
      var formCustomer = document.querySelector('div.field-planner-name_customers');
      var formContract =  document.querySelector('div.field-planner-info_contract');
      var formRequest =  document.querySelector('div.field-planner-info_request');
      window.pathContracts = {
          'url':'/contracts/lists?id=',
          'select':'select#planner-info_contract'
      };
      var pathRequests = {
          'url':'/requests/lists?id=',
          'select':'select#planner-name_customers',
          'flag':'one'
      };

      window.load = function (selector,select,url,flag,callback) {
        flag=flag||'';
        callback = callback || function (){};
        var  val = selector.options[selector.selectedIndex].value;
        if (val){
              $.post(url+val+'&='+flag,
                  function(data){
                      $(select).html(data);
                      callback();
              });
         }
      return null;
      };

        var loadRequests = function () {
            formCustomer.hidden = false;
            formContract.hidden = false;
            load(selectRequests,pathRequests.select,pathRequests.url,pathRequests.flag,loadContracts);
        };

        var loadContracts = function () {
            load(selectCustomers,pathContracts.select,pathContracts.url);
        };

      selectCustomers.addEventListener('change',loadContracts);
      selectRequests.addEventListener('change',loadRequests);

    };

    loadData();

})();