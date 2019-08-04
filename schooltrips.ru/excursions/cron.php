<?php
file_put_contents('cron.log', chr(10).date('d.m.Y H:i:s'), FILE_APPEND);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$dateField = "103924";
$statuses = array(
    array('from' => 18298384, 'to' => 18298387),
    array('from' => 18467524, 'to' => 18467527),
    //array('from' => 18534220, 'to' => 18534223),
);
auth_amoCRM($amo);
$updateLeads = array('update' => array());
$now = date('Y-m-d',time()+24*3600).' 00:00:00';
foreach ($statuses as $status){
    $leads = array();
    $offset = 0;
    echo 'start';
    do {
        $part = getLeadsByStatusId($amo, $status['from'], $offset);
        $leads = array_merge($leads,$part['response']['leads']);
        $offset +=500;
    } while ( count($part['response']['leads']) == 500);
    foreach ($leads as $key => $lead) {
        foreach ($lead['custom_fields'] as $field){
            if ($field['id'] === $dateField) {
                if ($field['values'][0]['value'] == $now){
                    echo 'update';
                    array_push($updateLeads['update'], array(
                        'id' => $lead['id'],
                        'updated_at' => time(),
                        'status_id' => $status['to']
                    ));
                }
            }
        }
    }
}

$leads = array();
$offset = 0;
do {
    $part = getLeadsByStatusId($amo, 18675424, $offset);
    $leads = array_merge($leads,$part['response']['leads']);
    $offset +=500;
} while ( count($part['response']['leads']) == 500);
foreach ($leads as $key => $lead) {
    foreach ($lead['custom_fields'] as $field){
        if ($field['id'] === $dateField) {
            $t = strtotime($field['values'][0]['value']);
            $t+=3600*19;
            if ($t < time()){
                echo 'update';
                array_push($updateLeads['update'], array(
                    'id' => $lead['id'],
                    'updated_at' => time(),
                    'status_id' => 19122352
                ));
            }
        }
    }
}

$leads = array();

$offset = 0;
do {
    $part = getLeadsByStatusId($amo, 17475193, $offset);
    $leads = array_merge($leads,$part['response']['leads']);
    $offset +=500;
} while ( count($part['response']['leads']) == 500);

$offset = 0;
do {
    $part = getLeadsByStatusId($amo, 21120444, $offset);
    if(!$part)
        break;
    $leads = array_merge($leads,$part['response']['leads']);
    $offset +=500;
} while ( count($part['response']['leads']) == 500);

foreach ($leads as $key => $lead) {
    $kur = false;
    $place = false;
    foreach ($lead['custom_fields'] as $field){
        if ($field['id'] == 578295) {
            $kur = true;
        }
        if ($field['id'] == 103892) {
            $place = true;
        }
    }
    foreach ($lead['custom_fields'] as $field){
        if ($field['id'] == $dateField) {
            $t = strtotime($field['values'][0]['value'])-11*60*60;
            if ($t < time()){
                echo 'update';
                if($kur&&$place)
                    array_push($updateLeads['update'], array(
                        'id' => $lead['id'],
                        'updated_at' => time(),
                        'status_id' => 18675424
                    ));
                else if($lead['status_id']!=21120444)
                    array_push($updateLeads['update'], array(
                        'id' => $lead['id'],
                        'updated_at' => time(),
                        'status_id' => 21120444
                    ));
            }
        }
    }
}

$leads = array();
$offset = 0;
do {
    $part = getLeadsByStatusId($amo, 22196523, $offset);
    $leads = array_merge($leads,$part['response']['leads']);
    $offset +=500;
} while ( count($part['response']['leads']) == 500);
foreach ($leads as $key => $lead) {
    echo 'lead $$$<br>';
    foreach ($lead['custom_fields'] as $field){
        if ($field['id'] === $dateField) {
            echo $field['values'][0]['value'];
            $t = strtotime($field['values'][0]['value']);
            $t-=3600*24*7;
            if ($t < time()){
                echo 'update';
                array_push($updateLeads['update'], array(
                    'id' => $lead['id'],
                    'updated_at' => time(),
                    'status_id' => 22196526
                ));
            }
        }
    }
}

file_put_contents(DIRNAME(__FILE__)."/log2.log", print_r($updateLeads,1));
updateLeadsByIds($amo, $updateLeads);