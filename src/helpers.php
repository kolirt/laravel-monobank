<?php

if (!function_exists('is_json')) {
    function is_json($string, $return_data = false)
    {
        $data = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
    }
}

if (!function_exists('toFixed')) {
    function toFixed($number, $fix = 2)
    {
        $arr = explode('.', $number);
        return isset($arr[1]) ? floatval($arr[0] . '.' . substr($arr[1], 0, $fix)) : $number;
    }
}
