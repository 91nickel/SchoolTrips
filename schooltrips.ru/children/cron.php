<?php
header('Access-Control-Allow-Origin: *');

require_once "./amocrm_functions.php";

file_put_contents('cron.log', date('d.m.y H:i:s').chr(10), FILE_APPEND);

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$amo['amocrm_phone_id']=95404;
$amo['amocrm_work_phone_id'] = 'WORK';

auth_amoCRM($amo);

$f = file_get_contents('list.json');
$f = json_decode($f,1);

$deals = file_get_contents('deals.json');
$deals = json_decode($deals,1);

foreach ($f as $key => $value) {
	foreach ($value as $key2 => $value2) {
		//find deal
		if(empty($deals[$key.'-'.$key2]))
		{
			$lead = [];
			$lead['name']=$value2['name'];
			$lead['contact_id']=$key;
			$lead['status']=20999820;
			$leadId = add_lead_amoCRM($amo,$lead);
			echo $leadId;
			echo json_encode($lead);
			$deals[$key.'-'.$key2] = ["id"=>$leadId,"status"=>20999820];
		}
		//move deal if it's not current status
		$d1 = time();
		$nd = $value2['birth'];
		$nd = explode('.', $nd);
		$nd = $nd[0].'.'.$nd[1].'.'.date('Y');
		$d2 = strtotime($nd);

		$status = 20999820;

		$days = (int)(($d2-$d1)/3600/24);
		if(($days<=14)&&($days>=0))
			$status = 20999823;
		if(($days<=7)&&($days>=0))
			$status = 20999826;

		if($status!=$deals[$key.'-'.$key2]['status'])
		{
			$lead = [];
			$lead['request'] = [];
			$lead['request']['leads'] = [];
			$lead['request']['leads']['update'] = [];
			$lead['request']['leads']['update'][0] = [];
			$lead['request']['leads']['update'][0]['status_id'] = $status;
			$lead['request']['leads']['update'][0]['id'] = $deals[$key.'-'.$key2]['id'];
			$lead['request']['leads']['update'][0]['name'] = $value2['name'];
			$lead['request']['leads']['update'][0]['last_modified'] = time();
			echo json_encode($lead);
			update_lead_amoCRM($amo,$deals[$key.'-'.$key2]['id'],$lead);
			$deals[$key.'-'.$key2]['status'] = $status;
		}

		//save moving
	}
}

$deals = file_put_contents('deals.json', json_encode($deals));

die;

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

foreach ($con['_embedded']['items'] as $key => $value) {
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==$amo['amocrm_phone_id'])
		{
			//echo $value2['values'][0]['value'].' - +7'.$tel."<br>";
			if(strpos($value2['values'][0]['value'], $tel)!==false)
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

//print_r($leads);

foreach ($leads as $key => $value) {
	if(!in_array($value['status_id'], [15562216, 20131045, 19607599, 17475193, 18675424, 18298357, 142]))
	{
		unset($leads[$key]);
	}
}

echo json_encode($leads);
?>