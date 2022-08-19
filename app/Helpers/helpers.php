<?php

use Illuminate\Support\Facades\DB;



function date_convert($data)
{
    $strDate = substr($data, 4, 11);
    $finaldt = date('Y-m-d H:i:s', strtotime($strDate));
    return $finaldt;
}

function allPages()
{
    return App\Model\Setting\PageSetting::where('publish', 1)->get();
}

function googleAnalytics()
{
    return App\Model\Setting\GoogleAnalytic::orderBy('id', 'desc')->first();
}

function minuteCalculate($from_date_time, $to_date_time)
{

    $to_time = strtotime($to_date_time);
    $from_time = strtotime($from_date_time);
    return round(abs($to_time - $from_time) / 60, 2);
}
