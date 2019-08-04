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
$fieldId = 570323;
$status_id = 18522238;
$leadId = $_GET['lead'];
$event = $_GET['event'];

auth_amoCRM($amo);
//$lead = getLeadsById($amo, $leadId);

//$customFields = $lead['response']['leads'][0]['custom_fields'];
//$currentStatusId = $lead['response']['leads'][0]['status_id'];
/*$eventArr = array_filter($customFields,function($a) use ($fieldId){
    return $a["id"] == $fieldId;
});
if (!empty($eventArr)){
    $eventArr = array_shift($eventArr);
    $event = $purchasedCurrent['values'][0]['value'];
}else{
    file_put_contents(DIRNAME(__FILE__)."/error.log", 'Не найдено поле event');
    exit();
}*/

$updateLead = array('update' => array(
    array(
        'id' => $leadId,
        'updated_at' => time(),
        'status_id' => $status_id,
        'custom_fields' => array(
            array(
                'id' => $fieldId,
                'values' => array(
                    array(
                        'value' => $event
                    )
                )
            )
        )
    )
));
updateLeadsByIds($amo, $updateLead);