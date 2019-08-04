<?php
$data['request']['unsorted'] = array(
    'category' => 'forms',
    'add' => array (
        array(
            'source' => 'www.my-awesome-site.com',
            'source_uid' => NULL,
            'data' => array(
                'leads' => array(
                    array(
                        'date_create' => date('U'),
                        'responsible_user_id' => '',
                        'name' => $subject,
                        'tags' => ''.$_SERVER['HTTP_HOST'].','.$skidka,
                        'notes' => array(
                        ),
                    ),
                ),
                'contacts' => array(
                    array(
                        'name' => $name,
                        'custom_fields' => array(
                            array(
                                'id' => 95404,
                                'values' => array(
                                    array(
                                        'enum' => 225732,
                                        'value' => $phone,
                                    ),
                                ),
                            ),

                        ),
                        'date_create' => 1446544971,
                        'responsible_user_id' => '102526',
                    ),
                ),
                'companies' => array(),
            ),
            'source_data' => array(
                'data' => array(
                    'name_1' => array(
                        'type' => 'text',
                        'id' => 'name',
                        'element_type' => '1',
                        'name' => 'ФИО',
                        'value' => $name,
                    ),
                    '427006_1' => array(
                        'type' => 'multitext',
                        'id' => '427006',
                        'element_type' => '1',
                        'name' => 'Телефон',
                        'value' => array(
                            $phone,
                        ),
                    ),
                ),
                'form_id' => 319,
                'form_type' => 1,
                'origin' => array(
                    'ip' => $_SERVER["REMOTE_ADDR"],
                    'datetime' => 'Tue Nov 01 2017 13:02:24 GMT+0300 (Russia Standard Time)',
                    'referer' => '',
                ),
                'date' => rand(000000000000, 999999999999),
                'from' => 'Заявка с сайта '.$_SERVER['HTTP_HOST'],
                'form_name' => 'My name for form',
            ),
        ),
    ),
);

$api_key = '01379d3c34cb08bdc9b945ad7a8dfbb977c1ed8d';
$login = 'Norkajtis@yandex.ru';
$subdomain ='schooltrip5'; #Наш аккаунт - поддомен

$link='https://'.$subdomain.'.amocrm.ru/api/unsorted/add/?api_key='.$api_key.'&login='.$login;

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
?>

