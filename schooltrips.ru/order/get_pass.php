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
$tel = substr($tel, 1);

if($tel=='')
	die;

$con = meth($amo, '/api/v2/contacts/?query='.urlencode($tel));

echo '/api/v2/contacts/?query='.urlencode($tel);

$contact = '';

$first = 0;

foreach ($con['_embedded']['items'] as $key => $value) {
	if($first==0)
		$first = $value['created_at'];
	if($value['created_at']<=$first)
	{
		$first = $value['created_at'];
		foreach ($value['custom_fields'] as $key2 => $value2) {
			if($value2['id']==$amo['amocrm_phone_id'])
			{
				if($value2['values'][0]['value']=='+7'.$tel)
				{
					$contact = $value;
				}
			}
		}
	}
}

//or create

if($contact=='')
{
	$new_con = [];
	$new_con['name'] = 'Новый контакт';
	$name = $new_con['name'];
	$new_con['phone'] = '+7'.$tel;
	$contact = add_contact_amoCRM($amo,$new_con);
}else{
	$name = $contact['name'];
	$contact = $contact['id'];
}

print_r($contact);

echo "<br>";

//create new pass
$pass = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
echo $pass;


$data = [];
$data['name'] = $name;
$data['custom_fields'] = [["id"=>581703,"values"=>[["value"=>$pass]]]];
update_contact_amoCRM($amo,$contact,$data);

//create new deal
$lead = ["name"=>'Восстановление пароля',"tags"=>"","status"=>20150335,"contact_id"=>$contact];
add_lead_amoCRM($amo,$lead);
?>