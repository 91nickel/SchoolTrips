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

$leads = m($amo, '/api/v2/leads?status[]=17475193&status[]=15562216');

foreach ($leads['_embedded']['items'] as $key => $value) {
	$v1 = '';
	$v2 = '';
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==103924)
		{
			$v1 = $value2['values'][0]['value'];
		}
		if($value2['id']==588912)
		{
			$v2 = $value2['values'][0]['value'];
		}
	}
	if($v1!='')
	{
		$v1 = date('d.m.Y',strtotime($v1));
		if($v1!=$v2)
		{
			echo 'change '.$v1."<br>";
			$data = ['update'=>[
				[
					'id'=>$value['id'],
					'updated_at'=>time(),
					'custom_fields'=>[
						['id'=>588912,'values'=>[['value'=>$v1]]]
					]
				]
			]];
			m_p($amo, '/api/v2/leads',$data);
		}
	}
}

$contacts = m_m($amo, '/api/v2/contacts', date('D, d M Y H:i:s',time()-600));

foreach ($contacts['_embedded']['items'] as $key => $value) {
	$p = '';
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==95404)
		{
			$p = $value2['values'][0]['value'];
		}
	}
	$p_n = str_replace(' ', '', $p);
	$p_n = str_replace('(', '', $p_n);
	$p_n = str_replace(')', '', $p_n);
	$p_n = str_replace('-', '', $p_n);
	$p_n = str_replace('@', '', $p_n);
	if($p_n!=$p)
	{
		echo 'change '.$value['id']."<br>";	
		$data = ['update'=>[
			[
				'id'=>$value['id'],
				'updated_at'=>time(),
				'custom_fields'=>[
					['id'=>95404,'values'=>[['value'=>$p_n,'enum'=>"WORK"]]]
				]
			]
		]];
		m_p($amo, '/api/v2/contacts',$data);
	}
}
?>