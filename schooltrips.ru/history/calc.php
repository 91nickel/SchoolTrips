<?
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

$account = m($amo, '/api/v2/account?with=custom_fields');

//get leads of this contact
$lead_id = $_REQUEST['leads']['status'][0]['id'];
if(isset($_REQUEST['leads']['add'][0]['id']))
	$lead_id = $_REQUEST['leads']['add'][0]['id'];
if(isset($_GET['lead_id']))
	$lead_id = $_GET['lead_id'];
$l = m($amo,'/api/v2/leads?id='.$lead_id)['_embedded']['items'][0];

if(isset($l['contacts']['_links']['self']['href']))
{
	$success = [];
	$isset = [];
	$notisset = [];
	$c = m($amo,$l['contacts']['_links']['self']['href']);
	foreach ($c['_embedded']['items'] as $key => $value) {
		$leads = m($amo,$value['leads']['_links']['self']['href']);
		foreach ($leads['_embedded']['items'] as $key2 => $value2) {
			if(isset($value2['catalog_elements']['_links']['self']['href']))
			{
				$els = m($amo,$value2['catalog_elements']['_links']['self']['href']);
				foreach ($els['_embedded']['items'] as $key3 => $value3) {
					if(($value2['status_id']==142)||($value2['status_id']==19122352))
					{
						$success[$value3['name']] = true;
					}else{
						$isset[$value3['name']] = true;
					}
				}
			}
		}

		foreach ($account['_embedded']['custom_fields']['contacts'][593774]['enums'] as $key2 => $value2) {
			if(isset($success[$value2]))
				continue;
			if(isset($isset[$value2]))
				continue;
			$notisset[$value2] = true;
		}

		$success_v = [];
		foreach ($success as $key2 => $value2) {
			$success_v[] = $key2;
		}

		$isset_v = [];
		foreach ($isset as $key2 => $value2) {
			$isset_v[] = $key2;
		}

		$notisset_v = [];
		foreach ($notisset as $key2 => $value2) {
			$notisset_v[] = $key2;
		}
		echo json_encode(m_p($amo,'/api/v2/contacts',['update'=>[['id'=>$value['id'],'updated_at'=>time(),'custom_fields'=>[['id'=>593772,'values'=>$success_v],['id'=>593774,'values'=>$notisset_v],['id'=>594474,'values'=>$isset_v]]]]]));
	}
}
?>