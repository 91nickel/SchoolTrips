<?php
//header('Access-Control-Allow-Origin: *'); 
sleep(2); 
mb_internal_encoding("UTF-8");
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
auth_amoCRM($amo);
file_put_contents(DIRNAME(__FILE__)."/hook.log", print_r($_REQUEST,1), FILE_APPEND);
$hook = $_REQUEST['leads'];
$leads = array();
$target = 'Yclients, запись клиента:';
$catalogId = 6687;
$dateFieldId = 572521;
$timeFieldId = 572531;
if (array_key_exists('add',$hook)){
    foreach ($hook['add'] as $add) {
        array_push($leads, $add);
    }
}
if (array_key_exists('status', $hook)){
    foreach ($hook['status'] as $status){
        array_push($leads,$status);
    }
}
if(isset($_GET['lead']))
{
    array_push($leads,array('id'=>$_GET['lead']));
}
$notes = array();
foreach ($leads as $lead){
    $leadId = $lead['id'];
    //array_push($notes,$lead['id'] => array());
    $leadNotes = getNotesLeds($amo, $leadId, 4);
    $notes[$leadId] = $leadNotes['_embedded']['items'];
}
//print_r($notes);
//die;
if (!empty($notes)){
    foreach ($notes as $id => $leads) {
        foreach ($leads as $note) {
            $text = $note['text'];
            if (stristr($text, $target)) {
                $found = $text;
            };
        }
        if ($found){
            echo 'found';
            file_put_contents(DIRNAME(__FILE__)."/hook.log", 'found '.chr(10), FILE_APPEND);
            $foundArray = explode("\n", $found);
            $placeName = trim($foundArray[3]);
            $placeName = mb_substr($placeName, 1);
            $placeName = trim($placeName);
            $placeName = mb_substr($placeName, 0, -1);
            $placeName = trim($placeName);
            $name = mb_substr(trim($foundArray[2]), 1);
            $name = mb_substr(trim($name), 0, -1);
            $term = "$placeName-$name";
            $dateTime = mb_substr(trim($foundArray[4]), 1);
            $dateTime = mb_substr(trim($dateTime), 0, -1);
            $date = mb_substr(trim($dateTime), 0, 10);
            $time = mb_substr(trim($dateTime), 11, 5);
            file_put_contents(DIRNAME(__FILE__) . "/result.log", $term, FILE_APPEND);
            file_put_contents(DIRNAME(__FILE__) . "/result.log", $date, FILE_APPEND);
            file_put_contents(DIRNAME(__FILE__) . "/result.log", $time, FILE_APPEND);
            $elements = getCatalogElements($amo, $catalogId, $term);
            $elements = $elements['_embedded']['items'];
            file_put_contents(DIRNAME(__FILE__) . "/search.log", print_r($elements, 1), FILE_APPEND);
            foreach ($elements as $element) {
                $dateResult = false;
                $timeResult = false;
                foreach ($element['custom_fields'] as $field) {
                    file_put_contents(DIRNAME(__FILE__) . "/date.log", $field['values'][0]['value'], FILE_APPEND);
                    //file_put_contents(DIRNAME(__FILE__) . "/time.log", $term);
                    if ($field['id'] === $dateFieldId) {
                        if ($field['values'][0]['value'] == $date) {
                            $dateResult = true;
                        }
                    }
                    if ($field['id'] === $timeFieldId) {
                        if ($field['values'][0]['value'] == $time) {
                            $timeResult = true;
                        }
                    }
                }
                if ($dateResult && $timeResult) {
                    $result = $element; // Найденный элемент с датой и временем
                }
            }
            if ($result){
                file_put_contents(DIRNAME(__FILE__)."/hook.log", 'элемент найден '.chr(10), FILE_APPEND);
                $request = array('request' => array(
                    'links' => array(
                        'link' => array(
                            array(
                                "from" => "leads",
                                "from_id" => $id,
                                "to" => "catalog_elements",
                                "to_id" => $result['id'],
                                "to_catalog_id" => $catalogId
                            )
                        )
                    )
                ));
                file_put_contents(DIRNAME(__FILE__) . "/request.log", print_r($request, 1), FILE_APPEND);
                setCatalogElement($amo, $request);
            }
        }
    }
}
file_put_contents(DIRNAME(__FILE__)."/notes.log", print_r($notes,1), FILE_APPEND);

file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$leadId);