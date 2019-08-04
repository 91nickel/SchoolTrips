<?
include '/home/i/intime/public_html/api/lib/main/amo.php';

file_put_contents('index.log', file_get_contents("php://input").chr(10).chr(10), FILE_APPEND);

$data = file_get_contents("php://input");
$data = json_decode($data,1);

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

auth_amoCRM($amo);

$res = m_p($amo,'/api/v2/contacts',["add"=>[
	[
		'name'=>$data['lead_data']['Ваше имя'],
		'custom_fields'=>[
			[
				'id'=>95404,
				'values'=>[
					[
						'value'=>$data['lead_data']['phone'],
						'enum'=> "WORK"
					]
				]
			],
			[
				'id'=>95406,
				'values'=>[
					[
						'value'=>$data['lead_data']['email'],
						'enum'=> "WORK"
					]
				]
			]
		]
	]
]]);

echo json_encode($res);
echo $res['_embedded']['items'][0]['id'];

$res = m_p($amo,'/api/v2/leads',["add"=>[
	[
		'name'=>'Карамель',
		'tags'=>'карамель',
		'status_id'=>15562204,
		'contacts_id'=>$res['_embedded']['items'][0]['id']
	]
]]);

echo json_encode($res);
echo $res['_embedded']['items'][0]['id'];

file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$res['_embedded']['items'][0]['id']);

$text = "";

foreach ($data['lead_data'] as $key => $value) {
	$text.=$key.": ".$value.chr(10);
}

$res = m_p($amo,'/api/v2/notes',["add"=>[
	[
		'element_id'=>$res['_embedded']['items'][0]['id'],
		'element_type'=>2,
		'note_type'=>4,
		'text'=>$text
	]
]]);
?>