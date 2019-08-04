<?

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//die;

$data = file_get_contents("https://".$user['main_user']['subdomain'].".amocrm.ru/api/v2/incoming_leads?login=".urlencode($user['main_user']['email'])."&api_key=".$user['main_user']['key']."&page_size=500");

$data = json_decode($data,1);

$fori=0;
foreach ($data['_embedded']['items'] as $key => $value) {
	echo 'ok<br>';
	if($fori>30)
		break;

	if(($value['category']!='form')&&($value['category']!='chat'))
		continue;

	file_put_contents('cron.log', json_encode($value).chr(10), FILE_APPEND);

	$login = $user['main_user']['email'];
	$api_key=$user['main_user']['key'];
    $data=array(
	  'accept' => array(
	    $value['uid']
	  ),
	  'user_id' => 1301478,
	  'status_id' => $value['category']=='form'?19705510:13850295
	);
	$subdomain=$user['main_user']['subdomain'];
	$link='https://'.$subdomain.'.amocrm.ru/api/v2/incoming_leads/accept?api_key='.$api_key.'&login='.$login;
	/* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Подробнее о
	работе с этой
	библиотекой Вы можете прочитать в мануале. */
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_HTTPHEADER,['Accept: application/json']);
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl);
	echo $out;

	$out = json_decode($out,1);

	print_r($out);
	
	/*$f = file_get_contents('data.json');
	$f = json_decode($f,1);
	file_put_contents('cron.log', $out['data'][$value['uid']]['leads'][0].chr(10),FILE_APPEND);
	$f[$out['data'][$value['uid']]['leads'][0]] = time();
	//$arch[$out['data'][$value['uid']]['leads'][0]] = time();
	$f = json_encode($f);
	file_put_contents('data.json',$f);*/
	//file_put_contents('arch.json',json_encode($arch));

	$fori++;
}

?>