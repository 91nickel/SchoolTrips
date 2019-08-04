<?php
header('Access-Control-Allow-Origin: *');

require_once "./amocrm_functions.php";
$amo['amocrm_phone_id']=95404;
$amo['amocrm_work_phone_id'] = 'WORK';

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

auth_amoCRM($amo);

//find contact
$tel = $_GET['tel'];
$tel = str_replace(' ', '', $tel);
$tel = str_replace('-', '', $tel);
$tel = str_replace('(', '', $tel);
$tel = str_replace(')', '', $tel);
$tel = substr($tel, 1);

$code = $_GET['code'];

if($tel=='')
	die;

$con = meth($amo, '/api/v2/contacts/?query='.urlencode($tel));

$contact = '';

foreach ($con['_embedded']['items'] as $key => $value) {
	$is1 = false;
	$is2 = false;
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==$amo['amocrm_phone_id'])
		{
			if($value2['values'][0]['value']=='+7'.$tel)
			{
				$is1=true;
			}
		}
		if($value2['id']==581703)
		{
			if($value2['values'][0]['value']==$code)
			{
				$is2=true;
			}
		}
	}
	if($is1&&$is2)
	{
		die(json_encode($value));
	}
}

echo 'false';
?>