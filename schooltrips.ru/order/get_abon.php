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

$leads = meth($amo, '/api/v2/leads/?status=20661496');

//print_r($leads);

$d = [];
$d['c'] = 0;
$d['abons'] = [];

//print_r($leads);

//print_r($con);



foreach ($leads['_embedded']['items'] as $key => $value) {
	$all = false;
	if(empty($value['main_contact']['id']))
		continue;
	if($value['main_contact']['id']!=$_GET['con'])
		continue;
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==584419)
		{
			$all = $value2['values'][0]['value'];
		}
		if($value2['id']==584421)
		{
			$pr = $value2['values'][0]['value'];
		}
	}
	if(!$all)
		continue;
	if($all-$pr>0)
	{
		$d['c'] = $all-$pr;
		$d['id'] = $value['id'];
		$d['all'] = $all;
		$d['abons'][] = ['c'=>$all-$pr,'all'=>$all];
	}
}

echo json_encode($d);
?>