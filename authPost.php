<?php

include_once "curl.php";

$post=[
    'login'=>'diablospb',
    'passwd'=>'maniak2344513'
];

$c = curl::app('https://www.yandex.ru')
    ->set(CURLOPT_HEADER, 1)
    ->set(CURLOPT_POSTFIELDS,http_build_query($post));




$data =  $c-> request('/');

echo "<pre>";
print_r($data);
echo "</pre>";

//$y = curl::app('http://yknow.ru');

