<?php
header('Access-Control-Allow-Origin: *');

require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$amo['amocrm_phone_id']=95404;
$amo['amocrm_work_phone_id'] = 'WORK';

auth_amoCRM($amo);

//find contact
$tel = $_GET['tel'];
$tel = str_replace(' ', '', $tel);
$tel = str_replace('-', '', $tel);
$tel = str_replace('(', '', $tel);
$tel = str_replace(')', '', $tel);
$tel = str_replace('+', '', $tel);
$tel = substr($tel, 1);

if($tel=='')
	die;

$con = meth($amo, '/api/v2/contacts/?query='.urlencode($tel));

//print_r($con);

$contact = '';

$leads = [];

$a = [];

foreach ($con['_embedded']['items'] as $key => $value) {
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==$amo['amocrm_phone_id'])
		{
			//echo $value2['values'][0]['value'];
			//echo $value2['values'][0]['value'].' - +7'.$tel."<br>";
			//echo strpos($value2['values'][0]['value'], $tel);
			if(strpos($value2['values'][0]['value'], $tel)!==false)
			{
				$contact = $value;
				//echo json_encode($contact);
				$a['id'] = $contact['id'];
				$a['name'] = $contact['name'];
				echo json_encode($a);
				die;
			}
		}
	}
}
?>