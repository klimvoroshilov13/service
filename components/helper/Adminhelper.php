<?php
/**
 * User: Kazakov_N
 * Date: 18.07.2018
 * Time: 20:23
 */

namespace app\components\helper;

class Adminhelper
{
    static function printArr($array,$str){
        $n=0;
        echo var_dump($str)."<br>";
        foreach ($array as $key=>$value){
            echo "[$key] => {$value} <br>";
            $n++;
        }
        echo "Кол-во элементов = ".$n."<br>";
        echo "&nbsp";
    }

    static function debugToConsole( $data, $context = 'Debug in Console' ) {

        ob_start();

        $output  = 'console.info( \'' . $context . ':\' );';
        $output .= 'console.log(' . json_encode( $data ) . ');';
        $output  = sprintf( '<script>%s</script>', $output );

        echo $output;
    }
 }