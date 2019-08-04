<?php
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
$linkFieldId = 572679;
$emailFieldId = 95406;
$ROPID = 2242528;
auth_amoCRM($amo);

file_put_contents(DIRNAME(__FILE__)."/hook2.log", print_r($_REQUEST,1));
$leadsids = array();
$hook = $_REQUEST['leads'];
if (array_key_exists('add',$hook)){
    foreach ($hook['add'] as $add) {
        array_push($leadsids, $add);
    }
}
if (array_key_exists('status', $hook)){
    foreach ($hook['status'] as $status){
        array_push($leadsids,$status);
    }
}
if(isset($_GET['id']))
    array_push($leadsids,array('id'=>$_GET['id']));

$update = array('update' => array());
foreach ($leadsids as $leadid){
    $id = $leadid['id'];
    $leads = getLeadsById($amo, $id);
    $leads = $leads['response']['leads'];
    //$contact = $contact[0];
    foreach ($leads as $lead) {
        $link = "http://nova-agency.ru/auto/schooltrip/rfibank/url.php?id=".$lead['id'];
        array_push($update['update'], array(
            'id' => $lead['id'],
            'updated_at' => time(),
            'status_id' => 15562216,
            'custom_fields' => array(
                array(
                    'id' => $linkFieldId,
                    'values' => array(
                        array('value' => $link)
                    )
                )
            )
        ));
	    updateLeadsByIds($amo, $update);
	}
}