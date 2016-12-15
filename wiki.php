<?php

include_once "curl.php";

$c = curl::app("https://en.wikipedia.org")
    ->set(CURLOPT_HEADER, 1)
//    для https вкл SSL
    ->ssl( 0);

$data = $c->request('wiki/Karnataka_State_Film_Award_for_Best_Sound_Recording');

var_dump($data);