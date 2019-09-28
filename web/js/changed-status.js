'use strict';
(function () {

    let changedStatus = function () {
        let viewTr= document.querySelectorAll('tr');
        let viewTd=viewTr[11].cells[1];
        let sizeString=0;
        let numElements=3; //Количество элементов в строке
        window.arrStatus = viewTd.innerText.split(';');
        viewTd.innerHTML='<table>'+stringHtml(arrStatus,numElements,sizeString)+'</table>'
    };

    let stringHtml=function (arr,num,size) {
        let string;
        arr.forEach(function (item,i,arr) {
        if  (i === size){
            let stringFirst='<tr>'+'<td>'+ item +'</td>';
        }else if (i === size + 1){
            let stringSecond='<td>'+ '&nbsp- '+ item + '</td>';
        }else if (item===''){
            let stringThird='<td>'+item +'</td>'+'</tr>';
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