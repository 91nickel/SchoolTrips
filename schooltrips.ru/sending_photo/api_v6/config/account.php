<?php
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];
$account = array(
    // поддомен amoCRM
    'domain' => $user['main_user']['subdomain'],
    // логин администратора
    'login' => $user['main_user']['email'],
    // api hash администратора
    'hash' => $user['main_user']['key'],
    'catalog_id' => 6687,
    'status_id' => 142,
    'cf_photo' => 576817,
);