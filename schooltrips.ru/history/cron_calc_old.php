<?
die;
include_once 'amocrm_lite.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

auth_amoCRM($amo);

$leads = [];
$offset = 0;
$statuses = ['15562216','17475193','21120444','18675424','19122352','142'];
while(1){
	$p = m($amo, '/api/v2/leads?status[]='.implode('&status[]=', $statuses).'&limit_rows=500&limit_offset='.$offset);
	$offset+=500;
	if(isset($p['_embedded']['items']))
		$leads = array_merge($leads,$p['_embedded']['items']);
	else
		break;
}

echo count($leads);
echo "<br>";

foreach ($leads as $key => $value) {
	if($value['pipeline']['id']!=679696)
	{
		unset($leads[$key]);
		continue;
	}
	if(empty($value['catalog_elements']))
		unset($leads[$key]);
}

echo count($leads);
echo "<br>";

file_put_contents('cron.json', json_encode($leads));

?>