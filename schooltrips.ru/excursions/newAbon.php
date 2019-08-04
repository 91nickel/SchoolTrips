<?php
header('Access-Control-Allow-Origin: *');
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
auth_amoCRM($amo);

if(empty($_REQUEST['abon']))
    die;

$con = $_REQUEST['con'];

//$con = m($amo,'/api/v2/contacts?id='.$con)['_embedded']['items'][0];

if($_REQUEST['sale']==0)
	$_REQUEST['sale'] = 1;

$data = [];
$data['add'] = [];
$data['add'][0] = [];
$data['add'][0]['name'] = 'Запись с NOVA ORDERS АБ';
$data['add'][0]['sale'] = $_REQUEST['sale'];
$data['add'][0]['responsible_user_id'] = $_REQUEST['user'];
$data['add'][0]['contacts_id'] = $con;

if($_REQUEST['sale']==1)
	$data['add'][0]['status_id'] = 17475193;
else
	$data['add'][0]['status_id'] = 15562216;

$data['add'][0]['custom_fields'] = [["id"=>103878,"values"=>[["value"=>$_REQUEST['p1']]]],["id"=>118758,"values"=>[["value"=>$_REQUEST['p2']]]],["id"=>584423,"values"=>[["value"=>$_REQUEST['abon']]]],["id"=>578497,"values"=>[["value"=>true]]]];

$lead = create_lead_amoCRM($amo,$data);

$lead = $lead['_embedded']['items'][0]['id'];

$data = [
    "from"=>"leads",
    "from_id"=>$lead,
    "to"=>"catalog_elements",
    "to_id"=>$_REQUEST['cat_id'],
    "to_catalog_id"=>6687
];

$data = ["request"=>["links"=>["link"=>[$data]]]];

link_catalog_amoCRM($amo,$data);

file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$lead);
file_get_contents('http://nova-agency.ru/auto/schooltrip/excursions/calc.php');
file_get_contents('http://nova-agency.ru/auto/schooltrip/excursions/calcAbon.php?id='.$_REQUEST['con']);

echo $lead;