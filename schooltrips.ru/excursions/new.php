<?php
ini_set('display_errors','Off');
$a = time();
header('Access-Control-Allow-Origin: *');
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

file_put_contents('new.log', date('d.m.Y H:i:s').chr(10).json_encode($_GET).chr(10).chr(10), FILE_APPEND);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
auth_amoCRM($amo);

if(empty($_GET['name']))
    die;

$resp = '1301478';

if(isset($_GET['resp']))
	$resp = $_GET['resp'];

//new
if($_GET['tel']!='')
	$c = m($amo,'/api/v2/contacts?query='.urlencode($_GET['tel']));

if(($_GET['email']!=''))
	if(empty($c['_embedded']['items']))
		$c = m($amo,'/api/v2/contacts?query='.urlencode($_GET['email']));

if(empty($c['_embedded']['items']))
{
	$data = [];
	$data['add'] = [];
	$data['add'][0] = [];
	$data['add'][0]['name'] = $_GET['name'];
	$data['add'][0]['responsible_user_id'] = $resp;
	$data['add'][0]['custom_fields'] = [["id"=>95404,"values"=>[["value"=>$_GET['tel'],"enum"=>"WORK"]]],["id"=>95406,"values"=>[["value"=>$_GET['email'],"enum"=>"WORK"]]]];

	$con = create_contact_amoCRM($amo,$data);

	//print_r($con);

	$con = $con['_embedded']['items'][0]['id'];
}else{
	$con = $c['_embedded']['items'][0]['id'];
}

$data = [];
$data['add'] = [];
$data['add'][0] = [];
$data['add'][0]['name'] = 'Запись с NOVA ORDERS';
if($_GET['abon']=='1')
	$data['add'][0]['name'] = 'Запись с NOVA ORDERS АБ';
$data['add'][0]['sale'] = $_GET['price'];
$data['add'][0]['contacts_id'] = $con;
$data['add'][0]['tags'] = $_GET['tags'];
$data['add'][0]['responsible_user_id'] = $resp;
$data['add'][0]['status_id'] = isset($_GET['status_id'])?$_GET['status_id']:15562216;
$data['add'][0]['custom_fields'] = [
	["id"=>103878,"values"=>[["value"=>$_GET['p1']]]],
	["id"=>574181,"values"=>[["value"=>$_GET['title']]]],
	["id"=>118758,"values"=>[["value"=>$_GET['p2']]]],
	["id"=>591242,"values"=>[["value"=>$_GET['promo']]]],
	["id"=>601782,"values"=>[["value"=>$_COOKIE['utm_source']]]],
	["id"=>601784,"values"=>[["value"=>$_COOKIE['utm_medium']]]],
	["id"=>601786,"values"=>[["value"=>$_COOKIE['utm_campaign']]]],
	["id"=>601788,"values"=>[["value"=>$_COOKIE['utm_content']]]],
	["id"=>601790,"values"=>[["value"=>$_COOKIE['utm_term']]]]
];

if(!empty($_GET['count']))
	array_push($data['add'][0]['custom_fields'], ["id"=>584419,"values"=>[["value"=>$_GET['count']]]]);

$lead = create_lead_amoCRM($amo,$data);

$lead = $lead['_embedded']['items'][0]['id'];

$data = [
    "from"=>"leads",
    "from_id"=>$lead,
    "to"=>"catalog_elements",
    "to_id"=>$_GET['cat_id'],
    "to_catalog_id"=>6687
];

$data = ["request"=>["links"=>["link"=>[$data]]]];

link_catalog_amoCRM($amo,$data);

exec("wget -b -O /dev/null -o /dev/null ".'http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$lead." &");
exec("wget -b -O /dev/null -o /dev/null ".'http://nova-agency.ru/auto/schooltrip/excursions/calc.php'." &");

if($_GET['sendpulse']=='yes')
{
	$an = post('https://api.sendpulse.com/oauth/access_token',['grant_type'=>'client_credentials','client_id'=>'1026cd140c3f9f5fe9f802e2d5736b53','client_secret'=>'8519a1a1eefa31d15c0f55f0ccf7bd15'],false);
	$an = post2('https://api.sendpulse.com/addressbooks/2145640/emails',["emails"=>[$_GET['email']]],$an['access_token']);
}

function post($link,$data,$a)
{
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	if($a)
	{
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    'Authorization: Bearer '.$a
		));
	}
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

	$code=(int)$code;
	$Response=json_decode($out,true);
	return $Response;
}

function post2($link,$data,$a)
{
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	if($a)
	{
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    'Authorization: Bearer '.$a
		));
	}
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

	$code=(int)$code;
	$Response=json_decode($out,true);
	return $Response;
}

function get($link,$a)
{
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	    'Authorization: Bearer '.$a
	));
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

	$code=(int)$code;
	$Response=json_decode($out,true);
	return $Response;
}

echo $lead;