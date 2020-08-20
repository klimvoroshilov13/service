<?php
/**
 * User: Kazakov_N
 * Date: 18.07.2018
 * Time: 20:01
 */

namespace app\components\helper;

use DateTime;


class Datehelper
{

    static function getDayRus($date)
    {
        $days = [
            'Воскресенье',
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота'
        ];
        return $days[strftime("%w", strtotime($date))];
    }

    static function setCurrentDate($format = 'd.m.Y H:i')
    {
        $currentDate = new DateTime();
        switch ($format){
            case'd.m.Y H:i': $date = $currentDate->format('d.m.Y H:i');
            break;
            case'Y-m-d H:i': $date = $currentDate->format('Y-m-d H:i');
            break;
            case'Y-m-d': $date = $currentDate->format('Y-m-d');
            break;
            case'd.m.Y': $date = $currentDate->format('d.m.Y');
            break;
            case'm': $date = $currentDate->format('m');
            break;
            case'Y': $date = $currentDate->format('Y');
            break;
            case'y': $date = $currentDate->format('y');
            break;
        }
        /** @var string $date */
        return $date;
    }

    static function getMonthRus($date)
    {
        // The list months
        $_monthsList = [
            ".01." => "января",
            ".02." => "февраля",
            ".03." => "марта",
            ".04." => "апреля",
            ".05." => "мая",
            ".06." => "июня",
            ".07." => "июля",
            ".08." => "августа",
            ".09." => "сентября",
            ".10." => "октября",
            ".11." => "ноября",
            ".12." => "декабря"
        ];
        // Replace the day of the month
        $_mD = date(".m.", strtotime($date)); //для замены
        return str_replace($_mD, " ".$_monthsList[$_mD]." ", $date);
    }

}
