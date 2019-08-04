<?php
header('Access-Control-Allow-Origin: *'); 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

auth_amoCRM($amo);

//file_put_contents('check.log', json_encode($_REQUEST).chr(10).chr(10), FILE_APPEND);

$contact = isset($_REQUEST['contacts']['update'])?$_REQUEST['contacts']['update'][0]['id']:$_REQUEST['contacts']['add'][0]['id'];
if(isset($_GET['id']))
	$contact = $_GET['id'];
$contact = get_contacts_by_id_amoCRM($amo, $contact);

$contact = json_decode($contact,1);

//file_put_contents('check.log', json_encode($contact).chr(10).chr(10), FILE_APPEND);

$is = false;

foreach ($contact['response']['contacts'][0]['custom_fields'] as $key => $value) {
	if($value['id']==95404)
	{
		$v = $value['values'][0]['value'];
		$n = $v;
		$n = str_replace(' ', '', $n);
		$n = str_replace('-', '', $n);
		$n = str_replace('(', '', $n);
		$n = str_replace(')', '', $n);
		$n = $n[0]==8?('+7'.substr($n, 1)):$n;
		$n = $n[0]==7?('+7'.substr($n, 1)):$n;
		$n = $n[0]==9?('+7'.$n):$n;
		$n = $n[0]==4?('+7'.$n):$n;
		if($n!==$v)
		{
			$data = array();
			$data['name'] = $contact['response']['contacts'][0]['name'];
			$data['custom_fields'] = array(array('id'=>95404,'values'=>array(array('value'=>$n,'enum'=>"MOB"))));
			$r = update_contact_amoCRM($amo,$contact['response']['contacts'][0]['id'],$data);
			print_r($r);
		}
	}
	if($value['id']==581703)
	{
		$is = true;
	}
}

if(!$is)
{
	$pass = rand(0,9).rand(0,9).rand(0,9).rand(0,9);

	$data = array();
	$data['name'] = $contact['response']['contacts'][0]['name'];
	$data['custom_fields'] = array(array('id'=>581703,'values'=>array(array('value'=>$pass))));
	$r = update_contact_amoCRM($amo,$contact['response']['contacts'][0]['id'],$data);
}

?>