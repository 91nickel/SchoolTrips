<?php
file_put_contents('check.log', json_encode($_REQUEST)."\n\n", FILE_APPEND);

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
$newStatusId = 17475193;
auth_amoCRM($amo);
file_put_contents(DIRNAME(__FILE__)."/hook.log", json_encode($_REQUEST).chr(10).chr(10), FILE_APPEND);
$update = array('update' => array());
$leadId = explode('_', $_REQUEST['order_id']);
$leadId = $leadId[0];
$leadId = explode('_', $leadId);
$leadId = $leadId[0];
$leads = getLeadsById($amo, $leadId);
$lead = $leads['response']['leads'][0];

file_put_contents(DIRNAME(__FILE__)."/check.log", json_encode($lead).chr(10).chr(10), FILE_APPEND);

if($lead['pipeline_id']==1209886)
    $newStatusId = 20310940;

if($lead['pipeline_id']==1023409)
    $newStatusId = 18703834;

if($lead['pipeline_id']==978916)
    $newStatusId = 18910363;

if($lead['pipeline_id']==1244002)
    $newStatusId = 20661496;

if($lead['status_id']==22196460)
    $newStatusId = 22196463;

if($lead['status_id']==22196520)
    $newStatusId = 22196523;

if($lead['status_id']==22196526)
    $newStatusId = 22196529;

if($lead['status_id']==22196526)
    $newStatusId = 22196529;

if($lead['pipeline_id']==882256)
    $newStatusId = 18298381;

//echo json_encode($lead);
echo $newStatusId;

$f = file_get_contents('finance/ok.json');
$f = json_decode($f,1);
if($f[$_REQUEST['order_id']]==true)
    die;
$f[$_REQUEST['order_id']] = true;
$f = json_encode($f);
file_put_contents('finance/ok.json', $f);

$f = file_get_contents('finance/pays.json');
$f = json_decode($f,1);
$sum = $_REQUEST['system_income'];
$sum = (int)$sum;
array_push($f, array("sum"=>$sum, "id"=>$leadId, 'order_id'=>$_REQUEST['order_id'], "date"=>date('d.m.y H:i:s')));
$f = json_encode($f);
file_put_contents('finance/pays.json', $f);

echo json_encode($lead['id'])."<br>";

echo json_encode($lead['pipeline_id']);
array_push($update['update'], array(
    'id' => $leadId,
    'updated_at' => time(),
    'status_id' => $newStatusId,
    'custom_fields' => array(
        /*array(
            'id' => $linkFieldId,
            'values' => array(
                array('value' => '')
            )
        )*/
    )
));
file_put_contents(DIRNAME(__FILE__)."/check2.log", json_encode($update).chr(10).chr(10), FILE_APPEND);
updateLeadsByIds($amo, $update);

function addDoc($data, $sheet) {
    global $sheet1;
    $link='https://script.google.com/macros/s/AKfycbw6qOPu0P0HWwPuURgoyhcExmB7QYz2lwZ4PWhNGcj6vnRq8s3a/exec';
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    //curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
    curl_setopt($curl,CURLOPT_POSTFIELDS,"doc=1Ym8XsD2o4TuLaxTwdFUin-MKf5MjUasdzcZ-P5KvswU&sheet=$sheet&data=".json_encode($data));
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    var_dump($out);
}

$sheet3 = [];
$f = json_decode($f,1);
$f = [$f[count($f)-1]];
foreach ($f as $value){
    if($value['sum']==0)
        continue;
    array_push($sheet3, [
        $value['date'], $value['sum'], "https://schooltrip5.amocrm.ru/leads/detail/".$value['id'], $lead['responsible_user_id']
    ]);
}

//print_r($sheet3);
addDoc($sheet3, 3);
?>
