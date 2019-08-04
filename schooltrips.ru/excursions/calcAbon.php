<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Access-Control-Allow-Origin: *');


require_once "../date/amocrm_lite.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$statuses = array(17475193,20131045,19607599,18675424,18298357,142,21120444);

auth_amoCRM($amo);

$leads = m($amo, '/api/v2/leads/?status=20661496');

$abons = [];

foreach ($leads['_embedded']['items'] as $key => $value) {
    if(empty($value['main_contact']))
        continue;
    if(isset($_GET['id']))
        if($value['main_contact']['id']!=$_GET['id'])
            continue;
    $abons[] = $value;
}

// get all leads by payed status
$leads = array();

echo 'ok';

if(empty($_GET['id']))
foreach ($statuses as $key2 => $value2) {
    $offset = 0;
    do {
        $part = m($amo, '/api/v2/leads?status='.$value2.'&limit_rows=500&limit_offset='.$offset);
        if(isset($part['_embedded']['items']))
            $leads = array_merge($leads,$part['_embedded']['items']);
        $offset += 500;
    } while ( count($part['_embedded']['items']) == 500);
}
else{
    $c = m($amo,'/api/v2/contacts?id='.$_GET['id']);
    foreach ($statuses as $key2 => $value2) {
        $offset = 0;
        do {
            $part = m($amo, $c['_embedded']['items'][0]['leads']['_links']['self']['href']);
            if(isset($part['_embedded']['items']))
                foreach ($part['_embedded']['items'] as $key3 => $value3) {
                    if($value3['status_id']==$value2)
                        $leads[] = $value3;
                }
            $offset += 500;
        } while ( count($part['_embedded']['items']) == 500);
    }
}

$tochange = [];

foreach ($abons as $key => $value) {
    $tochange[$value['id']] = ['name'=>$value['name'],'c'=>0];
    foreach ($leads as $key2 => $value2) {
        $is = false;
        $c=0;
        foreach ($value2['custom_fields'] as $key3 => $value3) {
            if($value3['id']==584423)
            {
                if($value3['values'][0]['value']==$value['id'])
                {
                    $is = true;
                }
            }
            if($value3['id']==103878)
            {
                $c = $value3['values'][0]['value'];
            }
        }
        if($is)
            $tochange[$value['id']]['c']+=$c;
    }
}

//echo count($leads);


$list = [];
$list['update'] = [];

foreach ($tochange as $key => $e) {
    $update = array();
    $update['id'] = $key;
    $update['name'] = $e['name'];
    $update['updated_at'] = time();
    $update['custom_fields'] = [];
    $update['custom_fields'][0] = [];
    $update['custom_fields'][0]['id'] = 584421;
    $update['custom_fields'][0]['values'] = [['value'=>$e['c']]];

    array_push($list['update'], $update);
}

echo json_encode($list);

m_p($amo, '/api/v2/leads', $list);







