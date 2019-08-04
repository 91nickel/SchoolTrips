<?php
header('Access-Control-Allow-Origin: *');

require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

$res = auth_amoCRM($amo);

$list = getListCatalog($amo, 6917);

//echo json_encode($list['_embedded']['items'][0]);

$json = array();

foreach ($list['_embedded']['items'] as $key => $value) {
    array_push($json, $value);
}

echo json_encode($json);