'use strict';
(function () {
    var changedStatus = function () {
        var viewTr= document.querySelectorAll('tr');
        var viewTd=viewTr[11].cells[1];
        var stringHtml;
        var sizeString=0;
        var numElements=3; //Количество элементов в строке
        window.arrStatus = viewTd.innerText.split(';');
        window.arrStatus.forEach(function (item,i,arrStatus) {
            if  (i === sizeString){
                var stringHtmlFirst='<tr>'+'<td>'+ item +'</td>';
            }else if (i === sizeString + 1){
                var stringHtmlSecond='<td>'+ '&nbsp- '+ item + '</td>';
            }else if (item===''){
                var stringHtmlThird='<td>'+item +'</td>'+'</tr>';
            }else{
                stringHtmlThird='<td>'+'&nbsp&nbsp/'+ item +'/'+'</td>'+'</tr>'
            }
            i===sizeString + (numElements-1) ? sizeString = sizeString + numElements:null;
            stringHtml===undefined ? stringHtml='':null;
            stringHtmlFirst===undefined ? stringHtmlFirst ='':null;
            stringHtmlSecond===undefined ? stringHtmlSecond ='':null;
            stringHtmlThird===undefined ? stringHtmlThird ='':null;
            stringHtml=stringHtml+stringHtmlFirst+stringHtmlSecond+stringHtmlThird;
        });
        window.stringHtml=stringHtml;
        viewTd.innerHTML='<table>'+stringHtml+'</table>'
    };

    // $(document).ready(changedStatus);
    changedStatus();
    console.log(window.arrStatus);
    console.log(window.stringHtml);
})();