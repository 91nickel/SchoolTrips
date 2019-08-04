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
$tel = tonorm($_GET['tel']);

if($tel=='')
	die;

$con = meth($amo, '/api/v2/contacts/?query='.urlencode($tel));

//print_r($con);

$contact = '';

$leads = [];

foreach ($con['_embedded']['items'] as $key => $value) {
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==$amo['amocrm_phone_id'])
		{
			//echo $value2['values'][0]['value'].' - +7'.$tel."<br>";
			if(strpos(tonorm($value2['values'][0]['value']), $tel)!==false)
			{
				$contact = $value;
				//print_r($contact['leads']['_links']['self']['href']);
				$n_leads = meth($amo, $contact['leads']['_links']['self']['href']);
				//print_r($n_leads['_embedded']['items']);
				if(isset($n_leads['_embedded']['items']))
					$leads = array_merge($leads, $n_leads['_embedded']['items']);
				//echo json_encode($leads);
				//echo "<br><br>";
			}
		}
	}
}

function tonorm($tel)
{
	$tel = str_replace(' ', '', $tel);
	$tel = str_replace('-', '', $tel);
	$tel = str_replace('(', '', $tel);
	$tel = str_replace(')', '', $tel);
	$tel = str_replace('+', '', $tel);
	$tel = substr($tel, 1);
	return $tel;
}

//print_r($leads);

foreach ($leads as $key => $value) {
	if(!in_array($value['status_id'], [15562216, 20131045, 19607599, 17475193, 18675424, 21120444, 18298357, 142, 19122352]))
	{
		unset($leads[$key]);
	}
}

echo json_encode($leads);
?>