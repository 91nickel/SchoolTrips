<?
include_once 'amocrm_lite.php';

die;

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

//get catalog list
$list = [];
$offset = 0;
while(1)
{
	$p = m($amo, '/api/v2/catalog_elements?catalog_id=6687&limit_rows=500&limit_offset='.$offset);
	$offset+=500;
	if(isset($p['_embedded']['items']))
		$list = array_merge($list,$p['_embedded']['items']);
	else
		break;
}
//get account with custom_fields and its enums
//$account = m($amo, '/api/v2/account?with=custom_fields');
//compare and add enums to custom_fields
$l_name = [];
foreach ($list as $key => $value) {
	$name = $value['name'];
	//$name = str_replace(' ', '', $name);
	//$name = strtolower($name);
	$l_name[$name] = $value['name'];
}
$enums = [];
foreach ($l_name as $key => $value) {
	$enums[] = ['value'=>$value];
}
//echo json_encode($enums);
file_put_contents('cf_add.json', json_encode([["action"=>"apply_changes","cf[edit][0][id]"=>"593772","cf[edit][0][type_id]"=>"5","cf[edit][0][enums]"=>$enums,"cf[edit][0][element_type]"=>"1"],["action"=>"apply_changes","cf[edit][0][id]"=>"593774","cf[edit][0][type_id]"=>"5","cf[edit][0][enums]"=>$enums,"cf[edit][0][element_type]"=>"1"],["action"=>"apply_changes","cf[edit][0][id]"=>"594474","cf[edit][0][type_id]"=>"5","cf[edit][0][enums]"=>$enums,"cf[edit][0][element_type]"=>"1"]]));
?>