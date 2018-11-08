<?php
/**
 * User: Kazakov_N
 * Date: 23.07.2017
 * Time: 17:04
 */
function saveVal($arrAll,$arrOne){
    $i=0;
    $select=0;
    foreach ($arrAll as $value){
        if ($value===$arrOne)$select=$i;
        $i=$i+1;
     }
  return $select;
}

function getDayRus($date)
{
    $days = array(
        'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
        'Четверг', 'Пятница', 'Суббота'
    );
    return $days[strftime("%w", strtotime($date))];
}

function setCurrentDate()
{
    $currentDate = new DateTime();
    return $currentDate->format('d.m.Y H:i');
}

function printArr($array,$str=null){
    $n=0;
    echo var_dump($str)."<br>";
    foreach ($array as $key=>$value){
        echo "[$key] => {$value} <br>";
        $n++;
    }
    echo "Кол-во элементов = ".$n."<br>";
    echo "&nbsp";
}