<?php
file_put_contents('cron2.log', chr(10).date('d.m.Y H:i:s'), FILE_APPEND);
require_once "amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//$arch = json_decode(file_get_contents('arch.json'),1);

auth_amoCRM($amo);
$offset = 0;
$data = [];
while(1)
{
    $p = meth_last_min($amo,'api/v2/notes?type=lead&note_type=102&limit_rows=500&limit_offset='.$offset);
    $p = json_decode($p,1);
    $offset+=500;
    if($p['_embedded']['items'])
        $data = array_merge($data,$p['_embedded']['items']);
    else
        break;
}

$f = file_get_contents('data.json');
$add = json_decode($f,1);
$f = json_decode($f,1);

echo "<br>";
echo count($add);
echo "<br>";

echo "<br>";
echo count($data);
echo "<br>";

foreach ($data as $key => $value) {
    if($value['created_at']<time()-60*60*12)
        continue;
    if(empty($f[$value['element_id']]))
        $f[$value['element_id']] = 0;
    if(($f[$value['element_id']]<$value['created_at']))
    {
        if($value['element_id']==16731792)
        {
            echo "<br>";
            echo json_encode($value);
            echo "<br>";
        }
        //if($value['created_at']>time()-60*60*12)
        if(empty($add[$value['element_id']]))
            $add[$value['element_id']] = 0;
        if($add[$value['element_id']]<$value['created_at'])
            $add[$value['element_id']] = $value['created_at'];
        //$arch[$value['element_id']] = $value['created_at'];
        /*
        $f[$value['element_id']] = $value['created_at'];
        $f = json_encode($f);
        echo "<br><br>";
        echo json_encode($value);
        file_put_contents('data.json',$f);

        $note = [];
        $note['id'] = $value['element_id'];
        $note['element_type'] = 2;
        $note['note_type'] = 4;
        $note['text'] = 'Добавлено в виджет';
        echo '<br><br>';
        print_r($note);
        echo '<br><br>';
        add_note_amoCRM($amo,$note);*/
    }
}

$offset = 0;
$data = [];
while(1)
{
    $p = meth_last_min($amo,'api/v2/notes?type=lead&note_type=103&limit_rows=500&limit_offset='.$offset);
    $p = json_decode($p,1);
    $offset+=500;
    if($p['_embedded']['items'])
        $data = array_merge($data,$p['_embedded']['items']);
    else
        break;
}

foreach ($data as $key => $value) {
    if($value['created_at']<time()-60*60*12)
        continue;
    /*
    echo json_encode($value['element_id']);
    $f = file_get_contents('data_del.json');
    $f = json_decode($f,1);
    echo "<br>val ".$f[$value['element_id']]."<br>";
    if($f[$value['element_id']]!=$value['created_at'])
    {
        $f[$value['element_id']] = $value['created_at'];
        $f = json_encode($f);
        echo "<br><br>";
        //echo json_encode($value);
        file_put_contents('data_del.json',$f);


        $note = [];
        $note['id'] = $value['element_id'];
        $note['element_type'] = 2;
        $note['note_type'] = 4;
        $note['text'] = 'Удалено из виджета, т.к. есть ответ';
        echo '<br><br>';
        print_r($note);
        echo '<br><br>';
        add_note_amoCRM($amo,$note);
    }*/

    if(empty($add[$value['element_id']]))
        $add[$value['element_id']] = 0;
    if($add[$value['element_id']]<$value['created_at'])
        unset($add[$value['element_id']]);
}

$offset = 0;
$data = [];
while(1)
{
    $p = meth_last_min2($amo,'api/v2/notes?type=contact&note_type=10&limit_rows=500&limit_offset='.$offset);
    $p = json_decode($p,1);
    $offset+=500;
    if($p['_embedded']['items'])
        $data = array_merge($data,$p['_embedded']['items']);
    else
        break;
}

$fori=0;
foreach ($data as $key => $value) {
    if($value['params']['call_status']==6)
    {
        $contact = m($amo,'/api/v2/contacts?id='.$value['element_id']);
        $contact = $contact['_embedded']['items'][0];
        //echo json_encode($contact);
        $leads = $contact['leads']['id'];
        foreach ($leads as $key2 => $value2) {
            if(empty($add[$value2]))
                $add[$value2] = 0;
            if($add[$value2]<$value['created_at'])
                $add[$value2] = $value['created_at'];
            $fori++;
        }
    }
}

echo $fori." - calls<br>";

$offset = 0;
$data = [];
while(1)
{
    $p = meth_last_min($amo,'api/v2/leads?filter[date_create][from]='.date('Y-m-d').'&status[]=13850295&status[]=19705510&limit_rows=500&limit_offset='.$offset);
    $p = json_decode($p,1);
    $offset+=500;
    if($p['_embedded']['items'])
        $data = array_merge($data,$p['_embedded']['items']);
    else
        break;
}

echo 'ccl '.count($data)."<br>";

foreach ($data as $key => $value) {
    if($value['created_at']<time()-60*60*12)
        continue;
    if(empty($add[$value['id']]))
        $add[$value['id']] = 0;
    if(($add[$value['id']]<$value['created_at']))
    {
        $add[$value['id']] = $value['created_at'];
    }
}

$wh = file_get_contents('addtomess.log');
$wh = explode(chr(10), $wh);
$fori=0;
foreach ($wh as $key => $value) {
    if($key>=count($wh)-2)
        break;
    if(($value=='')&&($wh[$key+1]!='')&&($wh[$key+2]!=''))
    {
        if(strtotime($wh[$key+1])<time()-60*60*12)
            continue;
        $fori++;
        if(empty($add[$wh[$key+2]]))
            $add[$wh[$key+2]] = 0;
        if($add[$wh[$key+2]]<strtotime($wh[$key+1]))
            $add[$wh[$key+2]] = strtotime($wh[$key+1]);
    }
}

echo 'wh '.$fori."<br>";

//get notes
$offset = 0;
$data = [];
while(1)
{
    $p = meth_last_min($amo,'api/v2/notes?type=lead&note_type=4&limit_rows=500&limit_offset='.$offset);
    $p = json_decode($p,1);
    $offset+=500;
    if($p['_embedded']['items'])
        $data = array_merge($data,$p['_embedded']['items']);
    else
        break;
}

$fori=0;
foreach ($data as $key => $value) {
    if($value['text']=='Удалено из виджета по кнопке отвечено')
    {
        if(isset($add[$value['element_id']]))
        {
            if($add[$value['element_id']]<$value['created_at'])
            {
                $fori++;
                unset($add[$value['element_id']]);
            }
        }
    }
}

echo 'del '.$fori."<br>";

$add = json_encode($add);
file_put_contents('data.json',$add);

function meth_last_min($account, $link)
{
    $link = 'https://'.$account['amocrm_domain'].'.amocrm.ru/'.$link;
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'if-modified-since: '.date('D, d M Y H:i:s',time()-60*60*12)
    ));
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $out;
}

function meth_last_min2($account, $link)
{
    $link = 'https://'.$account['amocrm_domain'].'.amocrm.ru/'.$link;
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'if-modified-since: '.date('D, d M Y H:i:s',time()-60*60*1)
    ));
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $out;
}

function m($account,$url)
{
  $link='https://'.$account['amocrm_domain'].'.amocrm.ru'.$url;
  $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
  #Устанавливаем необходимые опции для сеанса cURL
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
  curl_setopt($curl,CURLOPT_URL,$link);
  curl_setopt($curl,CURLOPT_HEADER,false);
  curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
  curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
  curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
  curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
   
  $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
  $out=json_decode($out,1);
  $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
  curl_close($curl);
  return $out;
}

function m_m($account,$url,$modif)
{
  $link='https://'.$account['amocrm_domain'].'.amocrm.ru'.$url;
  $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
  #Устанавливаем необходимые опции для сеанса cURL
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
  curl_setopt($curl,CURLOPT_URL,$link);
  curl_setopt($curl,CURLOPT_HEADER,false);
  curl_setopt($curl,CURLOPT_HTTPHEADER,array('IF-MODIFIED-SINCE: '.$modif));
  curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
  curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_amocrm.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
  curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
  curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
   
  $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
  $out=json_decode($out,1);
  $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
  curl_close($curl);
  return $out;
}