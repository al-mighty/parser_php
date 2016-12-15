<?php
//echo "LOL";
include_once "curl.php";

$c = curl::app('http://ntschool.ru')
    ->set(CURLOPT_HEADER, 1)
    ->set(CURLOPT_FOLLOWLOCATION, 1);

$data =  $c-> request('courses');

echo "<pre>";
print_r($data);
echo "</pre>";

//$y = curl::app('http://yknow.ru');

