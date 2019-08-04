<?php
define( 'ROOT', dirname(__FILE__));
file_put_contents(ROOT.'/cron.log', 'OK');
ini_set('log_errors', 1);
ini_set("display_errors", 1);
ini_set('display_startup_errors',1);
ini_set('error_log', ROOT . '/php-error.log');

include ROOT.'/api_v6/loader.php';
require_once ROOT.'/api_v6/config/account.php';

function checkLinksCatalogelements($account, $elementId, $catalogId) { 
	$request = array( 
		'links' => array( 
			array( 
				"from" => "catalog_elements", 
				"from_id" => $elementId, 
				"to" => "leads", 
				"from_catalog_id" => $catalogId 
			) 
		) 
	); 
	
	$link='https://'.$account['domain'].'.amocrm.ru/private/api/v2/json/links/list?'.http_build_query($request); 
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL 
	#Устанавливаем необходимые опции для сеанса cURL 
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); 
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); 
	curl_setopt($curl,CURLOPT_URL,$link); 
	curl_setopt($curl,CURLOPT_HEADER,false); 
	curl_setopt($curl,CURLOPT_COOKIEFILE, dirname(__FILE__).'/api_v6/temp/schooltrip5_cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_COOKIEJAR, dirname(__FILE__).'/api_v6/temp/schooltrip5_current.cache'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); 
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); 
	/* Выполняем запрос к серверу. */ 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную 
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); 
	curl_close($curl); 
	/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */ 
	$code=(int)$code; 
	$result = json_decode($out, true); 
	return $result['response']['links']; 
}

function updateLeads($account, $leads) {
	$link='https://'.$account['domain'].'.amocrm.ru/api/v2/leads'; 
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL 
	#Устанавливаем необходимые опции для сеанса cURL 
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); 
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); 
	curl_setopt($curl,CURLOPT_URL,$link); 
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST'); 
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads)); 
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json')); 
	curl_setopt($curl,CURLOPT_HEADER,false); 
	curl_setopt($curl,CURLOPT_COOKIEFILE, dirname(__FILE__).'/api_v6/temp/schooltrip5_cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_COOKIEJAR, dirname(__FILE__).'/api_v6/temp/schooltrip5_current.cache'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); 
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную 
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); 
	/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */ 
	$code=(int)$code; 
	$errors=array( 
		301=>'Moved permanently', 
		400=>'Bad request', 
		401=>'Unauthorized', 
		403=>'Forbidden', 
		404=>'Not found', 
		500=>'Internal server error', 
		502=>'Bad gateway', 
		503=>'Service unavailable' 
	); 
	try { 
		#Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке 
		if($code!=200 && $code!=204) { 
			throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code); 
		} 
	} catch(Exception $E) { 
		die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode()); 
	} 
	return json_decode($out, true); 
}

function getLeads($account, $url) {
	$link='https://'.$account['domain'].'.amocrm.ru'.$url; 
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL 
	#Устанавливаем необходимые опции для сеанса cURL 
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); 
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); 
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json')); 
	curl_setopt($curl,CURLOPT_HEADER,false); 
	curl_setopt($curl,CURLOPT_COOKIEFILE, dirname(__FILE__).'/api_v6/temp/schooltrip5_cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_COOKIEJAR, dirname(__FILE__).'/api_v6/temp/schooltrip5_current.cache'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__ 
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); 
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную 
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); 
	/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */ 
	$code=(int)$code; 
	$errors=array( 
		301=>'Moved permanently', 
		400=>'Bad request', 
		401=>'Unauthorized', 
		403=>'Forbidden', 
		404=>'Not found', 
		500=>'Internal server error', 
		502=>'Bad gateway', 
		503=>'Service unavailable' 
	); 
	try { 
		#Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке 
		if($code!=200 && $code!=204) { 
			throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code); 
		} 
	} catch(Exception $E) { 
		die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode()); 
	} 
	return json_decode($out, true); 
}

function updateDoc($row, $col) {
	$url = 'https://script.google.com/macros/s/AKfycbzgWX_jZ0YRm32TzNUXP60RBLVCQvn6ydySQxov1htxo_dtCeSv/exec';
	$params = [
		'doc' => '1FSJMTh6mtU7ItD1sKyhL6XS4HnAxD6VuPT8CfhpDdVU',
		'sheet' => '0',
		'row' => $row,
		'col' => $col,
		'val' => 'отправлено',
	];

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL 
	#Устанавливаем необходимые опции для сеанса cURL 
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); 
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0'); 
	curl_setopt($curl,CURLOPT_URL,$url . '?' . http_build_query($params)); 
	curl_setopt($curl,CURLOPT_HEADER,false); 
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0); 
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0); 
	/* Выполняем запрос к серверу. */ 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную 
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); 
	curl_close($curl);
}

$amo = Amo\CRM::instance($account);
$link = 'https://script.google.com/macros/s/AKfycbxXiXjGnYRMFAWFau-wWaOdN4PvSL4HG9pDlK74G3OqfnbRGWQ/exec?doc=1FSJMTh6mtU7ItD1sKyhL6XS4HnAxD6VuPT8CfhpDdVU&sheet=0';

$gData = file_get_contents($link);
$gData = json_decode($gData, 1);

unset($gData[0]);

$catalog_elements = $amo->catalog_elements()->get()->catalogId(6687)->run();

$result = [];
foreach ($gData as $gKey => $gValue) {
	if ( empty($gValue[0]) ||  empty($gValue[4])) {
		continue;
	}
    if ($gValue[8] == 'отправлено') {
        continue;
    }
	$gName = $gValue[4];

	$gDate = explode('T', $gValue[0])[0];
	$gDate = explode('-', $gDate);
	$gDate = implode('.', array_reverse($gDate));

	$gTime = explode('T', $gValue[3])[1];
	$gTime = substr( $gTime, 0 , 5);

	$gLink = $gValue[6];

	foreach ($catalog_elements as $key => $catalog_element) {

		$name = $catalog_element->raw()->name;
		$date = $catalog_element->customByName('Дата')->value;
		$date = date("d.m.Y", strtotime($date . "-3 hours"));
		$time = $catalog_element->customByName('Время экскурсии')->value;
		$time = date("H:i", strtotime($time . "-3 hours +2 minute"));

        if (($name == $gName) && ($date == $gDate) && ($time == $gTime)) {
			
			$elemId = $catalog_element->raw()->id;
			$result[$elemId] = [
				'elemId' => $elemId,
				'link' => $gLink,
				'gKey' => $gKey + 1
			];
		}
	}
}

$update = ['update' => []];
foreach ($result as $key => $value) {
	$leads = checkLinksCatalogelements($account, $key, $account['catalog_id']);
	foreach ($leads as $lkey => $lvalue) {
		$l = getLeads($account,'/api/v2/leads?id='.$lvalue['to_id']);
		$l = $l['_embedded']['items'][0];
		if($l['status_id']==19122352)
			array_push($update['update'], [
				'id' => $lvalue['to_id'], 
				'status_id' => $account['status_id'],
				'updated_at' => time(),
				'custom_fields' => [
					[
						'id' => $account['cf_photo'], 
						'values' => [
							['value' => $value['link']]
						]
					]
				]
			]);
	}
	updateDoc($value['gKey'], 9);
}
print_r($update);
if (!empty($update['update'])){
    updateLeads($account, $update);
}
