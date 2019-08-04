<?php
header('Access-Control-Allow-Origin: *');

if(empty($_REQUEST['all']))
    $_REQUEST['all'] = 'false';

$f = file_get_contents('list_cache'.$_REQUEST['all'].'.json');
$f = json_decode($f,1);
if((time()-$f['time']<60*5)&&(empty($_GET['reload'])))
{
    if(count($f['json'])>0)
        die(json_encode($f['json']));
}

require_once "./amocrm_functions.php";

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

$res = auth_amoCRM($amo);
$list = [];
$offset=0;

while(1)
{
    $p = getListCatalog($amo, 6687, $offset);
    if(isset($p['_embedded']['items']))
    {
        $list = array_merge($list, $p['_embedded']['items']);
        $offset+=500;
    }else{
        break;
    }
}

//echo json_encode($list['_embedded']['items'][0]);

$json = array();

foreach ($list as $key => $value) {
	$d = '';
	$t = '';
    foreach ($value['custom_fields'] as $key2 => $value2) {
        if($value2['id']==572521)
            $d = $value2['values'][0]['value'];
        if($value2['id']==572531)
            $t = $value2['values'][0]['value'];
    }
	if((strtotime($d.' '.$t)>time()+60*45)||($_REQUEST['all']=='true'))
        array_push($json, $value);
}

file_put_contents('list_cache'.$_REQUEST['all'].'.json', json_encode(['json'=>$json,'time'=>time()]));

echo json_encode($json);