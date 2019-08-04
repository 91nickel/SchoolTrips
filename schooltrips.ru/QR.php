<?

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$user=array(
  'USER_LOGIN'=>$user['main_user']['email'], #Ваш логин (электронная почта)
  'USER_HASH'=>$user['main_user']['key'] #Хэш для доступа к API (смотрите в профиле пользователя)
);

$an = meth('auth.php?type=json',$user);
if($an['auth']==1){

    //Получаем id
    if(isset($_GET['lead_id'])&&((int)$_GET['lead_id'])) {
        $id=(int)$_GET['lead_id'];
    }
    else throw new Exception("Введите  ID");

    //Получаем нужную сделку
    $current = methGet2('/api/v2/leads?id='.$id);
    if(empty($current)) throw new Exception("Данной сделки не найдено");

    //Параметры для вывода
    $arr=[
        'ФИО контакта'=>'',
        'Номер телефона'=>'',
        'Дата экскурсии'=>'',
        'Название экскурсии'=>'',
        'Количество детей'=>'',
        'Тип стоимости'=>'',
        'Статус сделки'=>''
    ];
    //Получение значений параметров для вывода
    $leads=$current["response"]["leads"][0]["custom_fields"];
    foreach ($leads as $lead){
        if($lead['name']=="Дата экскурсии"){
            $data=$lead["values"][0]["value"];
            $data=new DateTime($data);
            $data=date_format($data, 'Y-m-d');
            $arr[$lead['name']]=$data;
        }
        if($lead['name']=="Название экскурсии")
            $arr[$lead['name']]=$lead["values"][0]["value"];
        if($lead['name']=="Количество детей")
            $arr[$lead['name']]=$lead["values"][0]["value"];
        if($lead['name']=="Тип стоимости")
            $arr[$lead['name']]=$lead["values"][0]["value"];
        if($lead['name']=="Статус сделки")
            $arr[$lead['name']]=$lead["values"][0]["value"];
    }
    $contact=$current["response"]["leads"][0]["main_contact_id"];

    //Получаем контакт
    if($contact){
        $current = methGet2('/api/v2/contacts?id='.$contact);
        if(!empty($current)){
            $arr['ФИО контакта']=$current["response"]["contacts"][0]["name"];
            $customs=$current["response"]["contacts"][0]["custom_fields"];
            foreach ($customs as $custom){
                if($custom["code"]=="PHONE")
                    $arr['Номер телефона']=$custom ["values"][0]["value"];
            }
        }
    }
    //Вывод параметров на экран
    foreach ($arr as $key => $value){
        if(!empty($value)){
            $html.=$key.' : '.$value.'<br>';
        }
    }
    //updateDeal($id,679696,'142');
}


function updateDeal($id, $pipeline_id, $status_id){
	$leads = array('request'=>array('leads'=>array('update'=>array())));
	$leads['request']['leads']['update']=array(
	  array(
	  	'id'=>$id,
	  	'last_modified'=>time(),
	  )
	);
	if($status_id!='')
		$leads['request']['leads']['update'][0]['status_id'] = $status_id;
	if($pipeline_id!='')
		$leads['request']['leads']['update'][0]['pipeline_id'] = $pipeline_id;
	return meth('v2/json/leads/set',$leads);
}

function meth($meth, $data)
{
	global $auth;
	$subdomain="schooltrip5"; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/private/api/'.$meth;

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_tango.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_tango.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
	curl_close($curl); #Завершаем сеанс cURL

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
	try
	{
	  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
	  if($code!=200 && $code!=204)
	    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
	}
	catch(Exception $E)
	{
	  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
	}
	 
	/**
	 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
	 * нам придётся перевести ответ в формат, понятный PHP
	 */
	$Response=json_decode($out,true);
	$Response=$Response['response'];
	return $Response;
}

function methGet2($meth)
{
	global $auth;
	$subdomain="schooltrip5"; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/'.$meth;

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie_tango.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie_tango.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
	curl_close($curl); #Завершаем сеанс cURL

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
	try
	{
	  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
	  if($code!=200 && $code!=204)
	    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
	}
	catch(Exception $E)
	{
	  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
	}
	 
	/**
	 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
	 * нам придётся перевести ответ в формат, понятный PHP
	 */
	$Response=json_decode($out,true);
	return $Response;
}
?>
<div style="text-align: center">
<div style="margin: 50px; display: inline-block;background: #eee; padding: 20px; text-align: left; font-family: Arial">
	<?=$html;?>
</div>
</div>