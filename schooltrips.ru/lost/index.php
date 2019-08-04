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

$list = m_m($amo,'/api/v2/notes?type=contact&note_type=10',date('D, d M Y H:i:s',time()-2*60+10));

foreach ($list['_embedded']['items'] as $key => $value) {
	if($value['params']['call_status']==6)
	{
		$contact = m($amo,'/api/v2/contacts?id='.$value['element_id']);
		$contact = $contact['_embedded']['items'][0];
		//echo json_encode($contact);
		$leads = $contact['leads']['id'];
		sort($leads);
		echo $leads[count($leads)-1];
		echo "<br>";
		file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$leads[count($leads)-1]);
	}
}
?>