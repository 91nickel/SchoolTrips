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
$purchasedField = "570063";
$visitsField = "570065";
$leftField = "570067";
$successfullyStatusId = "142";

auth_amoCRM($amo);
$leadId = $_REQUEST['leads']['status'][0]['id'];
$lead = getLeadsById($amo, $leadId);
$customFields = $lead['response']['leads'][0]['custom_fields'];
$currentStatusId = $lead['response']['leads'][0]['status_id'];
file_put_contents(DIRNAME(__FILE__)."/custom.log", print_r($customFields,1));
$purchasedCurrent = array_filter($customFields,function($a) use ($purchasedField){
    return $a["id"] == $purchasedField;
});
$visitsCurrent = array_filter($customFields, function ($a) use ($visitsField) {
   return $a['id'] == $visitsField;
});
$leftCurrent = array_filter($customFields, function ($a) use ($leftField) {
    return $a['id'] == $leftField;
});
if (!empty($purchasedCurrent)){
    $purchasedCurrent = array_shift($purchasedCurrent);
    $purchased = $purchasedCurrent['values'][0]['value'];
}else{
    file_put_contents(DIRNAME(__FILE__)."/error.log", 'Не найдено значение с оплаченными экскурсиями');
    exit();
}

if (!empty($visitsCurrent)){
    $visitsCurrent = array_shift($visitsCurrent);
    $visits = $visitsCurrent['values'][0]['value'];
    file_put_contents(DIRNAME(__FILE__)."/visits.log", $visits);
}else{
    $visits = 0;
}

if (!empty($leftCurrent)){
    $leftCurrent = array_shift($leftCurrent);
    $left = $leftCurrent['values'][0]['value'];
}else{
    $left = 0;
}

$visitsNew = $visits+1;
$leftNew = (int)$purchased - (int)$visitsNew;
if ($leftNew <= 0){
    $status_id = $successfullyStatusId;
}else{
    $status_id = $currentStatusId;
}

$updateLead = array('update' => array(
    array(
        'id' => $leadId,
        'updated_at' => time(),
        'status_id' => $status_id,
        'custom_fields' => array(
            array(
                'id' => $purchasedField,
                'values' => array(
                    array(
                        'value' => $purchased
                    )
                )
            ),
            array(
                'id' => $visitsField,
                'values' => array(
                    array(
                        'value' => $visitsNew
                    )
                )
            ),
            array(
                'id' => $leftField,
                'values' => array(
                    array(
                        'value' => $leftNew
                    )
                )
            ),

        )
    )
));
updateLeadsByIds($amo, $updateLead);