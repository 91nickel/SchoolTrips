<?

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$pipeline_step_id = $_REQUEST['leads']['status'][0]['status_id'];
$element_id = $_REQUEST['leads']['status'][0]['id'];

if(isset($_GET['id']))
	$element_id = $_GET['id'];

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

$user=array(
  'USER_LOGIN'=>$user['main_user']['email'], #Ваш логин (электронная почта)
  'USER_HASH'=>$user['main_user']['key'], #Хэш для доступа к API (смотрите в профиле пользователя)
  'subdomain'=>$user['main_user']['subdomain']
);

file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$element_id);

//file_put_contents('mail_send.log', file_get_contents('mail_send.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).json_encode($_REQUEST));

$an = meth('/private/api/auth.php?type=json',$user);

if($an['response']['auth']==1)
{
	$lead = methGet('/api/v2/leads?id='.$element_id);
	//file_put_contents('mail_send.log', file_get_contents('mail_send.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).json_encode($lead['_embedded']['items'][0]));
	$contact = methGet('/api/v2/contacts?id='.$lead['_embedded']['items'][0]['main_contact']['id']);
	$contact_id = $lead['_embedded']['items'][0]['main_contact']['id'];
	//file_put_contents('mail_send.log', file_get_contents('mail_send.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).json_encode($contact['_embedded']['items'][0]));
	$name = $contact['_embedded']['items'][0]['name'];
	foreach ($contact['_embedded']['items'][0]['custom_fields'] as $key => $value) {
		if($value['name']=='Телефон')
			$phone = $value['values'][0]['value'];
		if($value['name']=='Email')
			$to = $value['values'][0]['value'];
	}
	foreach ($lead['_embedded']['items'][0]['custom_fields'] as $key => $value) {
		if($value['name']=='Сборная')
			$title = $value['values'][0]['value'];
		if($value['name']=='Групповая')
			$title = $value['values'][0]['value'];
		if($value['name']=='Место встречи(сбор)')
			$place = $value['values'][0]['value'];
		if($value['name']=='Куратор')
			$kur = $value['values'][0]['value'];
		if($value['name']=='Дата экскурсии')
			$date = $value['values'][0]['value'];
		if($value['name']=='Время начала экскурсии')
			$time = $value['values'][0]['value'];
		if($value['name']=='Стоимость с человека')
			$price = $value['values'][0]['value'];
		if($value['name']=='Тип стоимости')
			$type_st = $value['values'][0]['value'];
		if($value['name']=='Дополнительный участник')
			$dop = $value['values'][0]['value'];
		if($value['name']=='Ссылка на оплату')
			$link = $value['values'][0]['value'];
		if($value['name']=='Название экскурсии')
			$title = $value['values'][0]['value'];
		if($value['name']=='Ссылка на фото')
			$link_f = $value['values'][0]['value'];
	}
	$price = $lead['_embedded']['items'][0]['sale'];
	$title2 = str_replace('-', "<br/>", $title);
	$lead_id = $element_id;
	$date = explode(' 00', $date);
	$date = $date[0];
	$date = date('d.m.Y',strtotime($date));
	$date = $date.' '.$time;
	$type = 'mess';
	$link_f = str_replace('http://', '', $link_f);
	$link_f = str_replace('https://', '', $link_f);
	if($_GET['type']=='link')
		$type = 'link';
	$url = 'http://nova-agency.ru/auto/schooltrip/mail.php?t_id='.$_GET['t_id'].'&to='.urlencode($to).'&name='.urlencode($name).'&phone='.urlencode($phone).'&element_id='.urlencode($element_id).'&contact_id='.urlencode($contact_id).'&pipeline_step_id='.urlencode($pipeline_step_id).'&type='.urlencode($type).'&title='.urlencode($title).'&price='.urlencode($price).'&date='.urlencode($date).'&kur='.urlencode($kur).'&place='.urlencode($place).'&type='.urlencode($type_st).'&dop='.urlencode($dop).'&link='.urlencode($link).'&link_f='.urlencode($link_f).'&lead_id='.urlencode($lead_id).'&title2='.urlencode($title2).'&time='.urlencode($time);
	print_r($url);
	$an = file_get_contents($url);
	file_put_contents('mail_send.log', file_get_contents('mail_send.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).$url);
	$an = json_decode($an,true);
	file_put_contents('mail_send.log', file_get_contents('mail_send.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).json_encode($an));
	if($_GET['type']=='link')
	{
		//update lead with link
		$data = array('update'=>array(array('id'=>$element_id,'updated_at'=>time()*1000,'custom_fields'=>array(array('id'=>433537,'values'=>array(array('value'=>$an['attachments'][0]['view_link'])))))));
		meth('/api/v2/leads',$data);
	}
}

function meth($meth, $data)
{
	global $auth;
	global $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru'.$meth;

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
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

function methGet($meth)
{
	global $auth;
	global $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru'.$meth;

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
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