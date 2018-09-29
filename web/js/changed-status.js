'use strict';
(function () {

    var changedStatus = function () {
        var viewTr= document.querySelectorAll('tr');
        var viewTd=viewTr[11].cells[1];
        var sizeString=0;
        var numElements=3; //Количество элементов в строке
        window.arrStatus = viewTd.innerText.split(';');
        viewTd.innerHTML='<table>'+stringHtml(arrStatus,numElements,sizeString)+'</table>'
    };

    var stringHtml=function (arr,num,size) {
        var string;
        arr.forEach(function (item,i,arr) {
        if  (i === size){
            var stringFirst='<tr>'+'<td>'+ item +'</td>';
        }else if (i === size + 1){
            var stringSecond='<td>'+ '&nbsp- '+ item + '</td>';
        }else if (item===''){
            var stringThird='<td>'+item +'</td>'+'</tr>';
        }else{
            stringThird='<td>'+'&nbsp&nbsp/'+ item +'/'+'</td>'+'</tr>'
        }
        i===size + (num-1) ? size = size + num:null;
        string===undefined ? string='':null;
        stringFirst===undefined ? stringFirst ='':null;
        stringSecond===undefined ? stringSecond ='':null;
        stringThird===undefined ? stringThird ='':null;
        string=string+stringFirst+stringSecond+stringThird;
        });
       return string;
    };

    // $(document).ready(changedStatus);
    changedStatus();
    console.log(window.arrStatus);
    console.log(stringHtml());
})();