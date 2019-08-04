<?
//header('Access-Control-Allow-Origin: *');  
#Массив с параметрами, которые нужно передать методом POST к API системы

//die;

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user
//$user['subdomain'];
//$user['main_user']['key'];

$user=array(
  'USER_LOGIN'=>$user['main_user']['email'], #Ваш логин (электронная почта)
  'USER_HASH'=>$user['main_user']['key'], #Хэш для доступа к API (смотрите в профиле пользователя)
  'subdomain'=>'schooltrip5'
);

echo json_encode($user);

$an = meth('auth.php?type=json',$user);
echo 'ok';
echo json_encode($an);
if($an['auth']==1)
{
	echo 'Авторизация прошла успешно';
	$all = array();
	$a = true;
	$limit_offset = 0;
	while($a)
	{
		$f = methGet2('api/v2/tasks?responsible_user_id=1301478&limit_rows=500&limit_offset='.$limit_offset);
		$limit_offset+=500;
		$tasks = [];
		foreach ($f['_embedded']['items'] as $key => $value) {
			if($value['task_type']!=826249)
				continue;

			if($value['is_completed']==true)
				continue;

			$tasks[] = $value;
		}
		$all = array_merge($all,$tasks);

		if(count($f['_embedded']['items'])<500)
			$a = false;
	}

	foreach ($all as $key => $value) {
		if($value['task_type']!=826249)
			continue;

		if($value['is_completed']==true)
			continue;
		if($value['complete_till_at']<time())
		{
			echo 'send';
			//add note
			$data = array('add'=>array());
			$data['add'][0]=array('element_id'=>$value['element_id'],'element_type'=>2,'note_type'=>4,'text'=>'@whatsapp '.$value['text']);
			meth2('api/v2/notes',$data);
			//complete task
			$data = array('update'=>array());
			$data['update'][0] = array('id'=>$value['id'],'updated_at'=>time(),'text'=>$value['text'],'is_completed'=>true);
			meth2('api/v2/tasks',$data);
		}
	}
}

function findSettings()
{
	$current = methGet('v2/json/accounts/current');
	$data = $current['account']['custom_fields']['contacts'];
	$arr = array();
	foreach ($data as $key => $value) {
		$arr[$value['name']] = $value['id'];
	}
	$arr['first_status'] = $current['account']['leads_statuses'][0]['id'];
	return $arr;
}

function addNote($deal, $text, $user)
{
	$notes = array('request'=>array('notes'=>array('add'=>array())));
	$notes['request']['notes']['add']=array(
	  array(
	    'element_id'=>$deal,
	    'element_type'=>2,
	    'note_type'=>4,
	    'text'=>$text,
	    'responsible_user_id'=>$user,
	  )
	);
	return meth('v2/json/notes/set',$notes);
}

function addContact($name, $deal, $tel, $email, $fields)
{
	print_r($fields);
	$contact = array('request'=>array('contacts'=>array('add'=>array())));
	$contact['request']['contacts']['add']=array(
	  array(
	    'name'=>$name, #Имя контакта
	    //'last_modified'=>1298904164, //optional
	    'linked_leads_id'=>array( #Список с айдишниками сделок контакта
	      $deal
	    ), #Наименование компании
	    'custom_fields'=>array(
	      array(
	        #Телефоны
	        'id'=>$fields['Телефон'], #Уникальный идентификатор заполняемого дополнительного поля
	        'values'=>array(
	          array(
	            'value'=>$tel,
	            'enum'=>'WORK' #Мобильный
	          )
	        )
	      ),
	    )
	  )
	);
	return meth('v2/json/contacts/set',$contact);
}

function addCompany($company, $deal, $tel, $email, $city)
{
	$contact = array('request'=>array('contacts'=>array('add'=>array())));
	$contact['request']['contacts']['add']=array(
	  array(
	    'name'=>$company, #Имя компании
	    //'last_modified'=>1298904164, //optional
	    'linked_leads_id'=>array($deal),
	    'custom_fields'=>array(
	      array(
	        #Телефоны
	        'id'=>307269, #Уникальный индентификатор заполняемого дополнительного поля
	        'values'=>array(
	          array(
	            'value'=>$tel,
	            'enum'=>'WORK' #Мобильный
	          )
	        )
	      ),
	      array(
	        #E-mails
	        'id'=>307271,
	        'values'=>array(
	          array(
	            'value'=>$email,
	            'enum'=>'WORK', #Рабочий
	          )
	        )
	      ),
	      array(
	        #Адрес
	        'id'=>363849,
	        'values'=>array(
	          array(
	            'value'=>$city
	          )
	        )
	      ),
	    )
	  )
	);
	return meth('v2/json/company/set',$contact);
}

function addDeal($name, $price, $status_id, $user_id, $tags, $oplata)
{
	$leads = array('request'=>array('leads'=>array('add'=>array())));
	$leads['request']['leads']['add']=array(
	  array(
	    'name'=>$name,
	    'tags'=>$tags,
	    //'date_create'=>1298904164, //optional
	    'status_id'=>$status_id,
	    'price'=>$price,
	    'responsible_user_id'=>$user_id,
	    'custom_fields'=>array(
	      array(
	        'id'=>440813,
	        'values'=>array(
	          array(
	            'value'=>$oplata
	          )
	        )
	      )
	    )
	  )
	);
	return meth('v2/json/leads/set',$leads);
}

function findDeal($query)
{
	return methGet('v2/json/leads/list?query='.$query);
}

function linkContact($id, $deal)
{
	echo $id;
	$contact = array('request'=>array('links'=>array('link'=>array())));
	$contact['request']['links']['link']=array(
	  array(
	  	"from"=> "contacts",
        "from_id"=> $id,
        "to"=> "leads",
        "to_id"=> $deal
	  )
	);
	return meth('v2/json/links/set',$contact);
}

function updateContact($id, $name, $deal, $tel, $email, $fields)
{
	echo $id;
	$contact = array('request'=>array('contacts'=>array('update'=>array())));
	$contact['request']['contacts']['update']=array(
	  array(
	  	'id'=>$id,
	  	'last_modified'=>time(),
	    //'last_modified'=>1298904164,
	  )
	);
	if($name!='')
		$contact['request']['contacts']['update'][0]['name'] = $name;
	return meth('v2/json/contacts/set',$contact);
}

function updateCompany($id, $company, $deal, $tel, $email, $city, $inz, $vir)
{
	$contact = array('request'=>array('contacts'=>array('update'=>array())));
	$contact['request']['contacts']['update']=array(
	  array(
	  	'id'=>$id,
	  	'last_modified'=>time(),
	    'name'=>$company, #Имя компании
	    //'last_modified'=>1298904164, //optional
	    'linked_leads_id'=>array($deal),
	    'custom_fields'=>array(
	      array(
	        #Телефоны
	        'id'=>307269, #Уникальный индентификатор заполняемого дополнительного поля
	        'values'=>array(
	          array(
	            'value'=>$tel,
	            'enum'=>'WORK' #Мобильный
	          )
	        )
	      ),
	      array(
	        #E-mails
	        'id'=>307271,
	        'values'=>array(
	          array(
	            'value'=>$email,
	            'enum'=>'WORK', #Рабочий
	          )
	        )
	      ),
	      array(
	        #Адрес
	        'id'=>363849,
	        'values'=>array(
	          array(
	            'value'=>$city
	          )
	        )
	      ),
	      array(
	        #Адрес
	        'id'=>368131,
	        'values'=>array(
	          array(
	            'value'=>$inz
	          )
	        )
	      ),
	      array(
	        #Адрес
	        'id'=>368127,
	        'values'=>array(
	          array(
	            'value'=>$vir
	          )
	        )
	      ),
	    )
	  )
	);
	return meth('v2/json/company/set',$contact);
}

function updateDeal($id, $reg)
{
	$leads = array('request'=>array('leads'=>array('update'=>array())));
	$leads['request']['leads']['update']=array(
	  array(
	  	'id'=>$id,
	  	'last_modified'=>time()
	  )
	);
	if($name!='')
		$leads['request']['leads']['update'][0]['name'] = $name;
	if($status_id!='')
		$leads['request']['leads']['update'][0]['status_id'] = $status_id;
	if($price!='')
		$leads['request']['leads']['update'][0]['price'] = $price;
	if($tags!='')
		$leads['request']['leads']['update'][0]['tags'] = $tags;
	if($reg!='')
	{
		$leads['request']['leads']['update'][0]['custom_fields'] = array(
	      array(
	        'id'=>440083,
	        'values'=>array(
	          array(
	            'value'=>$reg
	          )
	        )
	      )
	    );
	}
	return meth('v2/json/leads/set',$leads);
}

function addTask($name, $deal, $user_id)
{
	$tasks = array('request'=>array('tasks'=>array('add'=>array())));
	$tasks['request']['tasks']['add']=array(
	  #Привязываем к сделке
	  array(
	    'element_id'=>$deal, #ID сделки
	    'element_type'=>2, #Показываем, что это - сделка, а не контакт
	    'task_type'=>1, #Звонок
	    'text'=>$name,
	    'responsible_user_id'=>$user_id,
	    'complete_till'=>time()+1800
	  )
	);
	return meth('v2/json/tasks/set',$tasks);
}

function meth($meth, $data)
{
	global $auth, $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
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

function meth2($meth, $data)
{
	global $auth;
	global $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/'.$meth;

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
	return $Response;
}

function methGet($meth)
{
	global $auth;
	global $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/private/api/'.$meth;

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
	$Response=$Response['response'];
	return $Response;
}

function methGet2($meth)
{
	global $auth;
	global $user;
	$subdomain=$user['subdomain']; #Наш аккаунт - поддомен
 
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