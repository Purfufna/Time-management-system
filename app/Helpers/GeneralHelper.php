<?php
/**
 * Created by PhpStorm.
 * User: ado
 * Date: 3.2.2016
 * Time: 23:02
 */

namespace App\Helpers;


class GeneralHelper
{
    public static function DateTimeFormat($datetime)
    {

        $format = 'yyyy-MM-dd';
        $date_unix = strtotime($datetime);
        $date = date($format, $date_unix);

        return $date;
    }
}