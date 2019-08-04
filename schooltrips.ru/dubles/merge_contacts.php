<?
header('Access-Control-Allow-Origin: *');
//https://dmitrybondar.ru/auto/schooltrip5/get_contacts.php
#Массив с параметрами, которые нужно передать методом POST к API системы

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

file_put_contents('gc.log', chr(10).date('d.m.Y H:i:s'), FILE_APPEND);

$t = file_get_contents('time.json');
$t_l = file_get_contents('time_last.json');

if($t_l>$t)
	die;

file_put_contents('time_last.json', time());

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$user=array(
  'USER_LOGIN'=>$user['main_user']['email'], #Ваш логин (электронная почта)
  'USER_HASH'=>$user['main_user']['key'] #Хэш для доступа к API (смотрите в профиле пользователя)
);

$an = meth('auth.php?type=json',$user);
if($an['auth']==1)
{
    //Ищем дубли $contacts - вытянем из файла

    $contacts = json_decode(file_get_contents('all_con.json'),1);

    $contacts = array_reverse($contacts);

    $dubles = array();
    $dubles_val = array();
    $fori=0;
    foreach ($contacts as $key => $value) {
    	$fori++;
    	$tel = '';
    	$email = '';
    	if(isset($value["phone"]))
        	$tel = $value["phone"];
        if(isset($value["email"]))
        	$email = $value["email"];
        $tel = str_replace('-', '', $tel);
        $tel = str_replace(' ', '', $tel);
        $tel = str_replace('(', '', $tel);
        $tel = str_replace(')', '', $tel);
        if(strlen($tel)>1)
        {
	        if($tel[0]=='8')
	            $tel = "+7".substr($tel, 1);
	        if($tel[0]=='7')
	            $tel = "+7".substr($tel, 1);
	        if($tel[0]=='9')
	            $tel = "+7".$tel;
	    }
        if(!empty($tel)){
            if(!isset($dubles[$tel]))
            {
                $dubles[$tel] = 1;
                $dubles_val[$tel] = [];
            }
            else{
                $dubles[$tel]++;
            }
            $dubles_val[$tel][] = $value;
        }

        if(!empty($email)){
            if(!isset($dubles[$email]))
            {
                $dubles[$email] = 1;
                $dubles_val[$email] = [];
            }
            else
                $dubles[$email]++;
            $dubles_val[$email][] = $value;
        }

    }

    $cd = array();

    $c = 0;

    foreach ($dubles as $key => $value) {
    	if($value>1)
    		$c++;
    }

    echo $c.' count';

    foreach ($dubles_val as $key => $value) {
    	if((count($value)>1)&&(count($value)<=5))
    	{
    		$el = [];
    		foreach ($value as $key2 => $value2) {
    			$el[] = $value2['id'];
    		}
    		$cd[] = $el;
    		if(count($cd)>100)
    			break;
    	}
    }

    $contacts = [];

    $dubles = [];

    echo "<br>";
    echo count($cd);

    $cd_json = json_encode($cd);
    file_put_contents('cd.json', $cd_json);

    $update = [];
    $add = [];

    echo json_encode($cd);

    foreach ($cd as $key => $value) {
    	if(count($value)<=5)
    	{
    		$last = [];
    		$ids = '?';
    		foreach ($value as $key2 => $value2) {
    			$ids.='id[]='.$value2.'&';
    		}
    		$contacts = methGet2('/api/v2/contacts/'.$ids);
    		if($ids=='?')
    			continue;
    		$contacts = $contacts["response"]["contacts"];
    		if(count($contacts)<2)
    			continue;
    		$contacts_id = [];
    		foreach ($contacts as $key2 => $value2) {
    			$contacts_id[$value2['id']] = $value2;
    		}
    		//find main
    		foreach ($value as $key2 => $value2) {
    			if(count($last)==0)
    				$last = $contacts_id[$value2];
    			if($last['date_create']<$contacts_id[$value2]['date_create'])
    				$last = $contacts_id[$value2];
    		}

    		//update main
    		$cf = [];
    		foreach ($value as $key2 => $value2) {
    			foreach ($contacts_id[$value2]['custom_fields'] as $key3 => $value3) {
    				$cf[$value3['id']] = $value3['values'][0]['value'];
    			}
    		}
    		$up = [];
    		$up['id'] = $last['id'];
    		$up['name'] = $last['name'];
    		$up['updated_at'] = time();
    		$up['custom_fields'] = [];
    		foreach ($last['custom_fields'] as $key2 => $value2) {
    			unset($cf[$value2['id']]);
    		}
    		foreach ($cf as $key2 => $value2) {
    			$up['custom_fields'][] = ["id"=>$key2,"values"=>[["value"=>$value2,"enum"=>"WORK"]]];
    		}
    		if(empty($last['linked_company_id']))
    			foreach ($value as $key2 => $value2) {
	    			if(isset($contacts_id[$value2]['linked_company_id']))
	    				$up['company_id'] = $contacts_id[$value2]['linked_company_id'];
	    		}
	    	$leads_id = [];
	    	foreach ($value as $key2 => $value2) {
    			if(isset($contacts_id[$value2]['linked_leads_id']))
    				$leads_id = array_merge($leads_id,$contacts_id[$value2]['linked_leads_id']);
    		}
    		//$leads_id = implode(',', $leads_id);
    		$up['leads_id'] = $leads_id;
    		//unset($up['leads_id']);
    		if($up['company_id']==0)
    			unset($up['company_id']);
    		$update[] = $up;
    		//update leads list
    		//delete other contacts
    		foreach ($contacts_id as $key2 => $value2) {
    			if($value2['id']!=$up['id'])
    			{
    				$nup = [];
    				$nup['id'] = $value2['id'];
		    		$nup['name'] = $value2['name'];
		    		$nup['updated_at'] = time();
		    		$nup['tags'] = "удалить";
		    		$update[] = $nup;
		    		echo '<br>del '.$nup['id'].' main '.$up['id'];
    			}
    		}
    		//note
    		$note = [];
    		$note['element_id'] = $up['id'];
    		$note['element_type'] = 1;
    		$note['note_type'] = 4;
    		$note['text'] = 'NOVA - выполнено объединение контактов';
    		$add[] = $note;

    		/*
    		var data = {};
			data.id = [];

			var com = '';
			var pr = 0;
			var con_list = [];
			var tags = [];
			var cfv = {};

			for(var t in contacts[i])
			{
				data.id.push(contacts[i][t].id);
				if(contacts[i][t].lead.company.id!=undefined)
					com = contacts[i][t].lead.company.id;
				if(contacts[i][t].lead.sale>0)
					pr = contacts[i][t].lead.sale;
				con_list.push(contacts[i][t].lead.contacts.id[0]);
				for(var j in contacts[i][t].lead.tags)
					tags.push(contacts[i][t].lead.tags[j].id);
				for(var j in contacts[i][t].lead.custom_fields)
					cfv[contacts[i][t].lead.custom_fields[j].id] = contacts[i][t].lead.custom_fields[j].values[0].value;
			}
			data.result_element = {};
			data.result_element.NAME = contacts[i][0].lead.name;
			data.result_element.MAIN_USER_ID = contacts[i][0].lead.responsible_user_id;
			data.result_element.COMPANY_UID = com;
			data.result_element.PRICE = pr+' руб';
			data.result_element.CONTACTS = con_list;
			data.result_element.TAGS = tags;
			data.result_element.cfv = cfv;
			console.log(data);
			$.post('https://schooltrip5.amocrm.ru/ajax/contacts/merge/save',data)
    		*/
    	}
    }

    echo "<br><br>";

    echo json_encode($update);

    echo "<br><br>";

    echo json_encode(updateContact($update));
    addNote($add);
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

function addNote($ar)
{
	$notes = array('request'=>array('notes'=>array('add'=>array())));
	$notes['request']['notes']['add']=$ar;
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

function updateContact($ob)
{
	$contact = ["update"=>$ob];
	return meth2('/api/v2/contacts',$contact);
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

function meth2($meth, $data)
{
	global $auth;
	$subdomain="schooltrip5"; #Наш аккаунт - поддомен
 
	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru'.$meth;

	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
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
	$subdomain="schooltrip5"; #Наш аккаунт - поддомен
 
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

function methGet2($meth){
    global $auth;
    $subdomain="schooltrip5"; #Наш аккаунт - поддомен

    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/'.$meth;

    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    //curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
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