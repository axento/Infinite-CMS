<?php

class Axento_DateTime
{
    public function dateFormat($datetime)
    {
        $day = substr($datetime,8,2);
        $month = substr($datetime,5,2);
        $year = substr($datetime,0,4);

        $hour = substr($datetime,11,2);
        $minutes = substr($datetime,14,2);

        $datetime = $day.'-'.$month.'-'.$year.' '.$hour.':'.$minutes;

        return $datetime;
    }

}