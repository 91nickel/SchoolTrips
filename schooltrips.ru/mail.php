<?

$to = array(array('email'=>$_GET['to']));
$name = $_GET['name'];
$phone = $_GET['phone'];
$link = 'https://schooltrip5.amocrm.ru/leads/detail/'.$_GET['element_id'];
$element_id = $_GET['element_id'];
$contact_id = $_GET['contact_id'];
$pipeline_step_id = $_GET['pipeline_step_id'];

function request($link,$data)
{
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
	return $out;
}

function requestGet($link)
{
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	 
	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
	curl_close($curl); #Завершаем сеанс cURL
	return $out;
}

$data = array();
$data['appkey'] = "LR2FDejHFJrsTcXDLd2N";
$data['email'] = "info@schoolschool.ru";
$data['password'] = "ceh30072016";
$data['hash'] = md5("appkey=".$data['appkey']."&secret=k2dSJamlTqLK&email=".$data['email']."&password=".$data['password']);
$an = request('https://api.b2bfamily.com/User/login',$data);
print_r($an);
$an = json_decode($an,true);
$apikey = $an['apikey'];
$json = file_get_contents('mail.json');
$json = str_replace(chr(10), '', $json);
$data = json_decode($json,true);
$data['apikey'] = $apikey;
$data['template_id'] = (int)$_GET['t_id'];
$data['to_emails'] = $to;
$data['client_name'] = $name;
$data['client_link'] = $link;
$data['client_phone'] = $phone;
$data['pipeline_step_id'] = (int)$pipeline_step_id;
$data['crm_data'][0]['element_id'] = $element_id;
$data['crm_data'][1]['element_id'] = $contact_id;
$data['crm_data'][0]['element_type'] = "0";
$data['crm_data'][1]['element_type'] = "2";
$data['custom_data']['userIdAmo'] = $element_id;

if($_GET['type']=='link')
	$data['type'] = 'link';

$template = requestGet('https://api.b2bfamily.com/Templates/get_template?apikey='.$apikey.'&id='.$_GET['t_id']);
$template = json_decode($template,true);

$data['subject'] = str_replace('{ИМЯ_КЛИЕНТА}', $name, $template['subject']);

$template['text'] = str_replace('{НАЗВАНИЕ}', $_GET['title'], $template['text']);
$template['text'] = str_replace('{НАЗВАНИЕ2}', $_GET['title2'], $template['text']);
$template['text'] = str_replace('{СТОИМОСТЬ}', $_GET['price'], $template['text']);
echo date('d.m.Y',strtotime($_GET['date']));
$template['text'] = str_replace('{ДАТА}', $_GET['date'], $template['text']);
$vz = '';
if(strpos(strtolower($_GET['title']),'музей'))
	$vz = '*Взрослый покупает себе билет в кассе музея самостоятельно (для музейных программ).';
if(strpos(strtolower($_GET['title']),'огород'))
	$vz = '*Взрослый покупает себе билет в кассе Ботанического сада самостоятельно (для Аптекарского огорода).';
if(strpos(strtolower($_GET['title']),'сад'))
	$vz = '*Взрослый сопровождающий - бесплатно (для Александровского сада).';
if(strpos(strtolower($_GET['title']),'кремль'))
	$vz = '*Взрослый билет - 500 руб.(для Кремля)';
if(strpos(strtolower($_GET['title']),'часть'))
	$vz = '*Дополнительный участник - 500 руб. (для Пожарной части)';
if(strpos(strtolower($_GET['title']),'цирк'))
	$vz = '*Дополнительный участник - 1200 руб. (для Цирка)';
$template['text'] = str_replace('{ВЗРОСЛЫЙ}', $vz, $template['text']);
$template['text'] = str_replace('{КУРАТОР}', $_GET['kur'], $template['text']);
$template['text'] = str_replace('{МЕСТО ВСТРЕЧИ}', $_GET['place'], $template['text']);
$template['text'] = str_replace('{ВРЕМЯ НАЧАЛА ЭКСКУРСИИ}', $_GET['time'], $template['text']);
$template['text'] = str_replace('{ССЫЛКА НА ОПЛАТУ}', $_GET['link'], $template['text']);
$template['text'] = str_replace('{ССЫЛКА}', $_GET['link_f'], $template['text']);
$template['text'] = str_replace('{ТИП}', $_GET['type'], $template['text']);
$template['text'] = str_replace('{ID}', $_GET['lead_id'], $template['text']);
if($_GET['dop']!='')
	$template['text'] = str_replace('{ДОП УЧАСТНИК}', 'Дополнительный участник: '.$_GET['dop'].'руб.', $template['text']);
else
	$template['text'] = str_replace('{ДОП УЧАСТНИК}', '', $template['text']);

$data['text'] = str_replace('{ИМЯ_КЛИЕНТА}', $name, $template['text']);

$att = array();
foreach ($template['attachments'] as $key => $value) {
	array_push($att, $value['id']);
}
$att = implode(';', $att);

$data['attachments'] = $att;
if($data['attachments']!='')
	$data['attachments'].=';';

file_put_contents('mail.log', file_get_contents('mail.log').chr(10).chr(10).date('d.m.Y H:i:s').chr(10).json_encode($data));

echo request('https://api.b2bfamily.com/mail/',$data);
//print_r(request('https://api.b2bfamily.com/Mail/generate_link',$data));

?>