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

$f = file_get_contents('list.json');
$f = json_decode($f,1);

if(count($f)==0)
{
	$list = [];
	$offset = 0;
	while(1)
	{
		$p = m($amo, '/api/v2/leads?filter[active]=1&limit_rows=500&limit_offset='.$offset);
		$offset+=500;
		if(isset($p['_embedded']['items']))
		{
			$ob = [];
			foreach ($p['_embedded']['items'] as $key => $value) {
				if($value['pipeline']['id']!=473331)
					continue;
				$ob[] = ['id'=>$value['id'],'main_contact'=>$value['main_contact']];
			}
			$list = array_merge($list,$ob);
		}
		else
			break;
	}

	file_put_contents('list.json', json_encode($list));
	die;
}

echo count($f);
echo "<br>";

$f_k = [];
foreach ($f as $key => $value) {
	$f_k[$value['id']] = $value;
}

if((date('H')>09)&&(date('H')<22))
{
	$fori=0;
	//echo file_get_contents('../messages/arch.json');

	$notes = [];
	$offset = 0;
	while(1)
	{
		$p = m_m($amo, '/api/v2/notes?note_type=4&type=lead&limit_rows=500&limit_offset='.$offset,date('D, d M Y H:i:s',time()-3600*24*3-600));
		$offset+=500;
		if(isset($p['_embedded']['items']))
		{
			$notes = array_merge($notes,$p['_embedded']['items']);
		}
		else
			break;
	}
	$lead_notes = [];
	$is_leads = [];
	foreach ($notes as $key => $value) {
		if(strpos($value['text'], 'виджета по кнопке отвечено')!==false)
		{
			if(!isset($lead_notes[$value['element_id']]))
				$lead_notes[$value['element_id']] = 0;
			if($lead_notes[$value['element_id']] < $value['created_at'])
				$lead_notes[$value['element_id']] = $value['created_at'];
		}
		if(strpos($value['text'], 'Воскрешение')!==false)
		{
			if(!isset($is_leads[$value['element_id']]))
				$is_leads[$value['element_id']] = 0;
			if($is_leads[$value['element_id']] < $value['created_at'])
				$is_leads[$value['element_id']] = $value['created_at'];
		}
	}
	echo "<br>";
	echo count($lead_notes);
	echo "<br>";
	$l_ids = [];
	$fori=0;
	foreach ($lead_notes as $key => $value) {
		if(isset($is_leads[$key]))
			if($value<$is_leads[$key])
			{
				echo 'del '.$key."<br>";
				continue;
			}
		if($fori>20)
			break;
		if($value<time()-3600*24*3)
		{
			$l_ids[] = $key;
			$fori++;
		}
	}
	echo "<br>";
	echo count($l_ids);
	echo "<br>";
	$l = m($amo,'/api/v2/leads?id[]='.implode('&id[]=', $l_ids));
	foreach ($l['_embedded']['items'] as $key => $value) {
		break;
		if($value['pipeline']['id']!=473331)
			continue;
		if($value['status_id']==142)
			continue;
		if($value['status_id']==143)
			continue;
		echo $value['id']."<br>";
		m_p($amo,'/api/v2/notes',["add"=>[["element_type"=>2,"note_type"=>4,"element_id"=>$value['id'],"text"=>"Воскрешение сделки"]]]);
		file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$value['id']);
		break;
	}
	/*$arch = json_decode(file_get_contents('../messages/arch.json'),1);
	echo count($arch);
	echo "<br>";
	foreach ($arch as $key => $value) {
		if($value<time()-3*24*3600)
		{
			if(isset($f_k[$key]))
			{
				echo 'voskres '.$key."<br>";
				m_p($amo,'/api/v2/notes',["add"=>[["element_type"=>2,"note_type"=>4,"element_id"=>$key,"text"=>"Воскрешение сделки"]]]);
				file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$key);
				break;
			}
		}
	}*/
}

$fori=0;
foreach ($f as $key => $value) {
	if($fori>10)
		break;
	$fori++;
	$l = m($amo,'/api/v2/leads?id='.$value['id'])['_embedded']['items'][0];
	if($l['pipeline']['id']!=473331)
	{
		unset($f[$key]);
		continue;
	}
	$contact = m($amo,$value['main_contact']['_links']['self']['href'])['_embedded']['items'][0];
	$leads = m($amo,$contact['leads']['_links']['self']['href']);
	$is = false;
	foreach ($leads['_embedded']['items'] as $key2 => $value2) {
		if(($value2['pipeline']['id']==679696)&&($value2['status_id']!=142))
		{
			$is = true;
		}
	}
	if($is)
	{
		echo $value['id']."<br>";
		m_p($amo,'/api/v2/leads',['update'=>[['id'=>$value['id'],'updated_at'=>time(),'status_id'=>142]]]);
		unset($f[$key]);
	}else{
		unset($f[$key]);
	}
}

file_put_contents('list.json',json_encode($f));
?>