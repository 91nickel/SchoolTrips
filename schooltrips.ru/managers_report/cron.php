<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: *');
file_put_contents('log.log', '1');
require_once "./amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

$result = [
  /*
   * 'id-managera' => [
   *    'name => 'имя менеджера',
   *    'newLeadsIds' => [],
   *    'newLeadsCount' => 'кол-во новых сделок'
   *    'statuses' => [
   *        'id-статуса' => [
   *            'name' => 'Имя статуса',
   *            'count' => 'Кол-во сделок',
   *            'budget' => 'Сумма всех сделок в этом статусе'
   *        ]
   *    ]
   * ]
   * */
];

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

auth_amoCRM($amo);

/*$leads = [];
$offsetLeads = 0;
do {
    $count = 0;
    $part = get_leads_amoCRM($amo, $offsetLeads);
    if(isset($part['_embedded']['items'])){
        $count = count($part['_embedded']['items']);
        foreach ($part['_embedded']['items'] as $key => $value) {
            array_push($leads, array('id'=>$value['id'],'responsible_user_id'=>$value['responsible_user_id']));
        }
        //$leads = array_merge($leads, $part['_embedded']['items']);
    }
    $offsetLeads += 500;
} while ( $count == 500);

$f = file_get_contents('http://nova-agency.ru/auto/schooltrip/rfibank/finance/pays.json');
$f = json_decode($f,1);

foreach ($f as $key => $value) {
    foreach ($leads as $key2 => $value2) {
        if($value['id']==$value2['id'])
            $f[$key]['resp'] = $value2['responsible_user_id'];
    }
}

$sheet3 = [];
foreach ($f as $value){
    if($value['sum']==0)
        continue;
    $d = explode(' ', $value['date']);
    $d = $d[0];
    //echo $d."-".date('d.m.y')."<br>";
    $d2 = date('d.m.y');
    if(isset($_GET['d2']))
        $d2 = $_GET['d2'];
    if($d==$d2)
    {
        //echo 'is date';
        array_push($sheet3, [
            $value['date'], $value['sum'], "https://schooltrip5.amocrm.ru/leads/detail/".$value['id'], $managers[$value['resp']]['name']
        ]);
    }
}

//print_r($sheet3);
addDoc($sheet3, 3);*/

$t = time();

if(isset($_GET['t']))
    $t = $_GET['t'];

$startDay = mktime(0, 0, 0, date("m", $t)  , date("d", $t) - 1, date("Y", $t));
$reportDate = date('d.m.Y', $startDay);
$endDay = mktime(0, 0, 0, date("m", $t)  , date("d", $t), date("Y", $t)) - 10;
$modif = mktime(0, 0, 0, date("m", $t)  , date("d", $t) - 2, date("Y", $t));;
$dateModif = date("D, d M Y H:i:s", $modif);

$managers = [];
$users = getUsers($amo);
$users = $users['_embedded']['users'];
foreach ($users as $user_id => $user){
    $managers[$user_id] = $user;
}

//Получаем сделки и записываем их id в массив
$leads = [];
$offsetLeads = 0;
do {
    $count = 0;
    $part = getLeadsByModif($amo, $offsetLeads, $dateModif);
    if(isset($part['_embedded']['items'])){
        $count = count($part['_embedded']['items']);
        $leads = array_merge($leads, $part['_embedded']['items']);
    }
    $offsetLeads += 500;
} while ( $count == 500);
$leadsModif = [];
foreach ($leads as $lead){
    $id = $lead['id'];
    $leadsModif[$id] = $lead;
}

//print_r($leads);

$notesType = [1,3];
$notesType = implode(',', $notesType);
//Получаем примечания с заданными id сделок
$notes = [];
$offsetNotes = 0;
do{
    $count = 0;
    $part = getNotesLeds($amo, $dateModif, $offsetNotes);
    if(isset($part['_embedded']['items'])) {
        $count = count($part['_embedded']['items']);
        $notes = array_merge($notes, $part['_embedded']['items']);
    }
    $offsetNotes += 500;
}while($count == 500);
$createdsLeadsNotes = [];

//Заполняем кол-во новых статусов
foreach ($notes as $note){
    $date = $note['created_at'];
    if (($date >= $startDay) && ($date <= $endDay)){
        $resp_id = $note['responsible_user_id'];
        $element_id = $note['element_id'];
        $note_type = $note['note_type'];
        if ($note_type == 3){
            $statusId = $note['params']['STATUS_NEW'];
            $sale = array_key_exists($element_id, $leadsModif)? $leadsModif[$element_id]['sale']: 0;
            if (!array_key_exists($resp_id, $result)){ //Если нет менеджера То добавляем
                $result[$resp_id] = [
                    'report_date' => $reportDate,
                    'statuses' => [
                        $statusId => [
                            'count' => 1,
                            'budget' => $sale
                        ]
                    ]
                ];
            }else{
                if (!array_key_exists('statuses', $result[$resp_id])){
                    $result[$resp_id]['statuses'] = [
                        $statusId => [
                            'count' => 1,
                            'budget' => $sale
                        ]
                    ];
                }else{
                    if (!array_key_exists($statusId, $result[$resp_id]['statuses'])){ // Если нет кол-ва, то добавляем
                        $result[$resp_id]['statuses'][$statusId] = [
                            'count' => 1,
                            'budget' => $sale
                        ];
                    }else{
                        $result[$resp_id]['statuses'][$statusId]['count']++;
                        $result[$resp_id]['statuses'][$statusId]['budget'] += $sale;
                    }
                }
            }
            //стататус +1
        }else if ($note_type == 1){

            if (!array_key_exists($resp_id, $result)){ //Если нет менеджера То добавляем
                $result[$resp_id]['newLeadsCount'] = 1;
                $result[$resp_id]['report_date'] = $reportDate;
            }else{
                if (!array_key_exists('newLeadsCount', $result[$resp_id])){
                    $result[$resp_id]['newLeadsCount'] = 1;
                }else{
                    $result[$resp_id]['newLeadsCount']++;
                }
            }

            //Значит, что это примечание создания сделки
            $element_id = $note['element_id'];
            if (!array_key_exists($resp_id, $result)){
                $result[$resp_id] = [
                    'newLeadsIds' => [$element_id]
                ];
            }else{
                if (!array_key_exists('newLeadsIds', $result[$resp_id])){
                    $result[$resp_id]['newLeadsIds'] = [$element_id];
                }else{
                    array_push($result[$resp_id]['newLeadsIds'], $element_id);
                }
            }
            //array_push()
        }

    }
}
//Добавляем учёт только, что созданных сделок
foreach ($result as $manager_id => $managerItem){
    if (array_key_exists('newLeadsIds', $managerItem)){
        if (!empty($managerItem['newLeadsIds'])){
            $ids = implode(",", $managerItem['newLeadsIds']);
            $managerLeads = getLeadsById($amo, $ids);
            $managerLeads = $managerLeads['_embedded']['items'];
            foreach ($managerLeads as $managerLead){
                $leadStatusId = $managerLead['status_id'];
                $leadRespId = $managerLead['responsible_user_id'];
                $sale = $managerLead['sale'];
                if (!array_key_exists($leadRespId, $result)){
                    $result[$leadRespId] = [
                        'report_date' => $reportDate,
                        'statuses' => [
                            $leadStatusId => [
                                'count' => 1,
                                'budget' => $sale
                            ]
                        ]
                    ];
                }else{
                    if (!array_key_exists('statuses', $result[$leadRespId])){
                        $result[$leadRespId]['statuses'] = [
                            $leadStatusId => [
                                'count' => 1,
                                'budget' => $sale
                            ]
                        ];
                    }else{
                        if (!array_key_exists($leadStatusId, $result[$leadRespId]['statuses'])){ // Если нет кол-ва, то добавляем
                            $result[$leadRespId]['statuses'][$leadStatusId] = [
                                'count' => 1,
                                'budget' => $sale
                            ];
                        }else{
                            $result[$leadRespId]['statuses'][$leadStatusId]['count']++;
                            $result[$leadRespId]['statuses'][$leadStatusId]['budget'] += $sale;
                        }
                    }



                }
            }
        }
    }

}

$statuses = [];
$pips = getPip($amo);
$pips = $pips['_embedded']['pipelines'];
foreach ($pips as $pip){
    foreach ($pip['statuses'] as $statsus_id => $status){
        $statuses[$statsus_id] = [
            'name' => $status['name']."(".$pip['name'].")",
            'sort' => $status['sort']
        ];
    }
}



foreach ($result as $manager_id => $res){
    $result[$manager_id]['name'] = $managers[$manager_id]['name']." ". $managers[$manager_id]['last_name'];
    foreach ($statuses as $key => $status){
        if (array_key_exists($key, $result[$manager_id]['statuses'])){
            $result[$manager_id]['statuses'][$key]['name'] = $statuses[$key]['name'];
            $result[$manager_id]['statuses'][$key]['sort'] = $status['sort'];
        }else{
            $result[$manager_id]['statuses'][$key] =[
                'name' => $status['name'],
                'count' => 0,
                'budget' => 0,
                'sort' => $status['sort']
            ];
        }
    }
    /*foreach ($res['statuses'] as $idst => $status){
        $result[$manager_id]['statuses'][$idst]['name'] = $statuses[$idst]['name'];
    }*/
}
/*echo "<pre>";
    print_r($result);
echo "</pre>";*/

/*
 * [
 *  [date, name, count],
 *  [date, name, count]
 * ]
 * */


///////////+=============================================== ОТПРАВКА В G DOC===================================================
///////////+=============================================== ОТПРАВКА В G DOC===================================================
///////////+=============================================== ОТПРАВКА В G DOC===================================================
///////////+=============================================== ОТПРАВКА В G DOC===================================================
$sheet1 = [];
//print_r($result);
foreach ($result as $item){
    array_push($sheet1, [
       $item['report_date'], $item['name'], (string)$item['newLeadsCount']
    ]);
}
$add1 = [
    'doc' =>'1Ym8XsD2o4TuLaxTwdFUin-MKf5MjUasdzcZ-P5KvswU',
    'sheet'=> 1,
    'data'=> $sheet1
];
//echo json_encode($add1);
//print_r($sheet1);
addDoc($sheet1, 1);

$sheet2 = [];
foreach ($result as $value){
    foreach ($value['statuses'] as $status){
        if($status['budget']!=0)
            array_push($sheet2, [
                $value['report_date'], $value['name'], $status['name'], $status['count'], $status['budget']
            ]);
    }
}

addDoc($sheet2, 2);


///////////+=============================================== ОТПРАВКА В G DOC===================================================
///////////+=============================================== ОТПРАВКА В G DOC===================================================
///////////+=============================================== ОТПРАВКА В G DOC===================================================