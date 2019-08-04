<?
include_once 'amocrm_lite.php';

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

$offset = 0;
$tasks = [];

while(1)
{
	$p = m($amo, '/api/v2/tasks?responsible_user_id=1301478&limit_rows=500&limit_offset='.$offset);
	$offset+=500;
	if(isset($p['_embedded']['items']))
	{
		$tasks = array_merge($tasks,$p['_embedded']['items']);
	}else 
		break;
}

$up = [];

$users = m($amo, '/api/v2/account?with=users')['_embedded']['users'];
$current = file_get_contents('current.txt');
$n_u = [];
foreach ($users as $key => $value) {
	$n_u[] = $value;
}
if(empty($current))
	$current = 0;

echo count($tasks);
$fori=0;
foreach ($tasks as $key => $value) {
	if($current>count($n_u)-1)
		$current = 0;

	if($value['is_completed']==true)
		continue;
	if($value['task_type']==1210515)
		continue;
	if($value['task_type']==826249)
		continue;

	echo json_encode($value);

	$fori++;

	if($fori>200)
		break;

	$up[] = ['id'=>$value['id'],'updated_at'=>time(),'text'=>$value['text'],'responsible_user_id'=>$n_u[$current]['id']];

	$current ++;
}

//echo "<br><br>";

//echo json_encode($up);

m_p($amo, '/api/v2/tasks',['update'=>$up]);

file_put_contents('current.txt', $current);


?>