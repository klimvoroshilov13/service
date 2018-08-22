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
        $days = array(
            'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
            'Четверг', 'Пятница', 'Суббота'
        );
        return $days[strftime("%w", strtotime($date))];
    }

    static function setCurrentDate()
    {
        $currentDate = new DateTime();
        return $currentDate->format('d.m.Y H:i');
    }

}