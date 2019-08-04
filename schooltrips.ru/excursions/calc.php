<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Access-Control-Allow-Origin: *');


require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$statuses = array(17475193, 21120444, 18675424, 19122352);

auth_amoCRM($amo);


// get all leads by payed status
$leads = array();

foreach ($statuses as $key2 => $value2) {
    $offset = 0;
    do {
        $part = getLeadsByStatusId($amo, $value2, $offset);
        if(isset($part['response']['leads']))
            $leads = array_merge($leads,$part['response']['leads']);
        $offset += 500;
    } while ( count($part['response']['leads']) == 500);
}

//echo count($leads);

// get all exc
$exc = array();
$offset = 0;
do {
    $part = getListCatalog($amo, 6687, $offset);
    if(isset($part['_embedded']['items']))
        $exc = array_merge($exc,$part['_embedded']['items']);
    $offset += 500;
} while ( count($part['_embedded']['items']) == 500);

$list = [];
$list['update'] = [];

foreach ($exc as $key => $e) {
    $all = 0;
    $kids = 0;
    $adult = 0;
    $last_all = 0;
    $last_kids = 0;
    $last_adult = 0;
    $is_all = '';
    $is_kids = '';
    $is_adult = '';

    echo $e['name']."<br><br>";
    echo json_encode($e['leads'])."<br><br>";

    foreach ($e['custom_fields'] as $key => $value) {
        if($value['id']==572521)
            echo $value['values'][0]['value']." ";
        if($value['id']==572531)
            echo $value['values'][0]['value']."<br><br>";
    }

    if(count($e['leads'])>0)
    foreach ($e['leads']['id'] as $key2 => $lead_id) {
        foreach ($leads as $key3 => $lead) {
            if($lead['id']==$lead_id)
            {
                echo $lead_id."<br>";
                $k = 0;
                $a = 0;
                //echo $lead_id."<br>";
                //echo json_encode($lead['custom_fields'])."<br>";
                foreach ($lead['custom_fields'] as $field){
                    if ($field['id'] == 103878) {
                        $k = $field['values'][0]['value'];
                        //echo '$K found '.$k;
                    }
                }
                foreach ($lead['custom_fields'] as $field){
                    if ($field['id'] == 118758) {
                        $a = $field['values'][0]['value'];
                    }
                }
                $all += (int)$k;
                $all += (int)$a;
                $kids += (int)$k;
                $adult += (int)$a;

                echo $k."<br>";
                echo $a."<br><br>";
                echo $kids." kids<br>";

                echo $all."<br>";
            }
        }
    }


    foreach ($e['custom_fields'] as $field){
        if ($field['id'] === 572521) {
            $t = strtotime($field['values'][0]['value']);
            if($t<time()-24*3600)
                continue 2;
        }
        if ($field['id'] === 578757) {
            $is_all = (int)$field['values'][0]['value'];
        }
        if ($field['id'] === 578907) {
            $is_kids = (int)$field['values'][0]['value'];
        }
        if ($field['id'] === 578909) {
            $is_adult = (int)$field['values'][0]['value'];
        }
    }

    if($is_all!='')
        $last_all = $is_all - $all;
    else
        $last_all = '';

    if($is_kids!='')
        $last_kids = $is_kids - $kids;
    else
        $last_kids = '';

    if($is_adult!='')
        $last_adult = $is_adult - $adult;
    else
        $last_adult = '';

    $update = array();
    $update['id'] = $e['id'];
    $update['updated_at'] = time();
    $update['catalog_id'] = $e['catalog_id'];
    $update['name'] = $e['name'];
    $update['custom_fields'] = [];
    $update['custom_fields'][0] = [];
    $update['custom_fields'][0]['id'] = 578759;
    $update['custom_fields'][0]['values'] = [['value'=>$kids]];
    $update['custom_fields'][1]['id'] = 579267;
    $update['custom_fields'][1]['values'] = [['value'=>$adult]];
    $update['custom_fields'][2]['id'] = 579273;
    $update['custom_fields'][2]['values'] = [['value'=>$last_all]];
    $update['custom_fields'][3]['id'] = 578761;
    $update['custom_fields'][3]['values'] = [['value'=>$last_kids]];
    $update['custom_fields'][4]['id'] = 579269;
    $update['custom_fields'][4]['values'] = [['value'=>$last_adult]];

    array_push($list['update'], $update);
}

//echo json_encode($list);

updateListEl($amo, $list);







