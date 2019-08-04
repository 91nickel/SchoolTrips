<?php
file_put_contents('cron.log', chr(10).date('d.m.Y H:i:s'), FILE_APPEND);
header('Access-Control-Allow-Origin: *');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

file_put_contents('cron_t.log', date('d.m.Y H:i:s').chr(10), FILE_APPEND);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$catalogId = 6687;
$dateFieldId = 572521;
$timeFieldId = 572531;
$pricePerFieldId = 572523;
$typePriceFieldId = 572525;
$dopFieldId = 572527;
$curatorFieldId = 572529;
$placeFieldId = 572533;
auth_amoCRM($amo);

/*$leads = array();
//$leads = getLeadsByStatusId($amo,15562216,0);
//$leads2 = getLeadsByStatusId($amo,17475193,0);
$leads = array_merge($leads,$leads2);
$ar_ids = [];
foreach ($leads['_embedded']['items'] as $key => $value) {
    break;
    $ar_ids[]=$value['id'];
    $is = false;
    foreach ($value['custom_fields'] as $key2 => $value2) {
        if($value2['id']==574181)
            $is = true;
    }
    if(!$is)
        $_GET['link'] = $value['id'];
}*/

//echo $_GET['link'];
/*$offset = 0;
do {
    $lead = get_leads_amoCRM($amo, $offset, '');
    file_put_contents(DIRNAME(__FILE__)."/lead.log", print_r($lead,1));
    $leads = array_merge($leads,$lead['response']['leads']);
    $offset +=500;
} while ( count($lead['response']['leads']) == 500);

file_put_contents(DIRNAME(__FILE__)."/leads.log", print_r($leads,1));

foreach ($leads as $lead){
    if (!$lead['deleted'] && !$lead['date_close']){
        if ($lead['status_id'] != 142){
            array_push($activeLeads, $lead);
        }
    }
}*/
$elements = array();
$page = 0;
do {
    $element = getAllCatalogElements($amo, $catalogId, $page);
    $elements = array_merge($elements,$element['_embedded']['items']);
    $page+=500;
} while ( count($element['_embedded']['items']) == 500);

echo count($elements);

$resultlinks = array();
$update = array('update' => array(

));
if(isset($_GET['link']))
{
    $l = checkLinksLeads($amo, $_GET['link'], $catalogId);
    $l = $l[0]['to_id'];
}
$fori2=0;

$fe = file_get_contents('fe.json');
$fe = json_decode($fe,1);

foreach ($elements as $element){
    foreach ($element['custom_fields'] as $field){
            switch ($field['id']){
                case $dateFieldId:
                    $date = $field['values'][0]['value'];
                    break;
            }
    }
    if(isset($_GET['link'])&&($element['id']!=$l))
        continue;
    if(isset($_GET['cat'])&&($element['id']!=$_GET['cat']))
        continue;
    if(empty($_GET['link']))
        if(isset($fe[$element['id']]))
            if(time()-$fe[$element['id']]<60*30)
                continue;
    $date = '';
    $time = '';
    $pricePer = '';
    $typePrice = '';
    $dop = '';
    $curator = '';
    $place = '';
    $name = $element['name'];
    if (1){
        foreach ($element['custom_fields'] as $field){
            switch ($field['id']){
                case $dateFieldId:
                    $date = $field['values'][0]['value'];
                    break;
                case $timeFieldId:
                    $time = $field['values'][0]['value'];
                    break;
                case $pricePerFieldId:
                    $pricePer = $field['values'][0]['value'];
                    break;
                case $typePriceFieldId:
                    $typePrice = $field['values'][0]['value'];
                    break;
                case $dopFieldId:
                    $dop = $field['values'][0]['value'];
                    break;
                case $curatorFieldId:
                    $curator = $field['values'][0]['value'];
                    break;
                case $placeFieldId:
                    $place = $field['values'][0]['value'];
                    break;
            }
        }
        if((((strtotime($date.' '.$time)-3600*24)>time())||((strtotime($date.' '.$time))<time()))&&empty($_GET['link']))
        {
            continue;
        }
        /*if(empty($_GET['link'])&&(($curator=='')||($place=='')))
            continue;*/
        //file_put_contents('cron_t.log', $element['name']." ".$date." ".$time.chr(10), FILE_APPEND);
        //if(empty($_GET['link']))
            //if($dateFieldId)
        $fori2++;
        if($fori2>=5)
            break;
        $fe[$element['id']] = time();
        $links = checkLinksCatalogelements($amo, $element['id'], $catalogId);
        if(isset($_GET['link']))
        {
            $is = false;
            if (!empty($links)){
                foreach ($links as $link){
                    if($link['to_id']==$_GET['link'])
                        $is = true;
                }
            }
            if(!$is)
                continue;
            $links = array(array('to_id'=>$_GET['link']));
        }

        //print_r($links);
        
        if (!empty($links)){
            foreach ($links as $link){
                $leadId = $link['to_id'];
                $lead = getLeadsById($amo, $leadId);
                $lead = $lead['response']['leads'][0];
                $c = 0;
                foreach ($lead['custom_fields'] as $key => $value) {
                    if($value['id']==103878)
                        $c = $value['values'][0]['value'];
                }
                $catalogId = 6687;
                $up_el = array(
                    'id' => $leadId,
                    'updated_at' => time(),
                    'custom_fields' => array(
                        array(
                            'id' => 103924,
                            'values' => array(
                                array('value' => $date)
                            )
                        ),
                        array(
                            'id' => 103878,
                            'values' => array(
                                array('value' => $c)
                            )
                        ),
                        array(
                            'id' => 118724,
                            'values' => array(
                                array('value' => $time)
                            )
                        ),
                        array(
                            'id' => 118760,
                            'values' => array(
                                array('value' => $pricePer)
                            )
                        ),
                        array(
                            'id' => 578293,
                            'values' => array(
                                array('value' => $typePrice)
                            )
                        ),
                        array(
                            'id' => 571625,
                            'values' => array(
                                array('value' => $dop)
                            )
                        ),
                        array(
                            'id' => 578295,
                            'values' => array(
                                array('value' => $curator)
                            )
                        ),
                        array(
                            'id' => 103892,
                            'values' => array(
                                array('value' => $place)
                            )
                        ),
                        array(
                            'id' => 574181,
                            'values' => array(
                                array('value' => $name)
                            )
                        )
                    )
                );
                $tags = ['карамель'];
                foreach ($lead['tags'] as $key5 => $value5) {
                    $tags[] = $value5['name'];
                }
                echo implode(',', $tags);
                if(strpos($name, 'фабрика')!==false)
                    $up_el['tags'] = implode(',', $tags);
                /*if($lead['status_id']==18829168)
                    $up_el['status_id'] = 18855172;*/
                array_push($update['update'], $up_el);
            }
        }
    }
}
file_put_contents('fe.json', json_encode($fe));
//echo $fori2;
//echo "<br><br>";
//echo json_encode($update);
file_put_contents('cron_t.log', json_encode($update).chr(10), FILE_APPEND);
updateLeadsByIds($amo, $update);