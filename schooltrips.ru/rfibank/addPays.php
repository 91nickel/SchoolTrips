<?php
file_put_contents('check.log', json_encode($_REQUEST['order_id'])."\n\n", FILE_APPEND);

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

$file = file_get_contents('finance/pays.json');
$file = json_decode($file,1);
$sheet3 = [];
foreach ($file as $key => $value) {
    $d = $value['date'];
    $d = explode(' ', $d);
    $d = $d[0];
    if($d!=$_GET['d'])
        continue;
    if($value['sum']==0)
        continue;
    $leads = getLeadsById($amo, $value['id']);
    $lead = $leads['response']['leads'][0];

    array_push($sheet3, [
            $value['date'], $value['sum'], $lead['name']." https://schooltrip5.amocrm.ru/leads/detail/".$value['id'], $lead['responsible_user_id']
    ]);

    //print_r($sheet3);
}
print_r($sheet3);
addDoc($sheet3, 3);
?>
