<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$f = json_decode(file_get_contents('cron.json'),1);

echo count($f);

$fori=0;
foreach ($f as $key => $value) {
	if($fori>10)
		break;
	file_get_contents('http://nova-agency.ru/auto/schooltrip/history/calc.php?lead_id='.$value['id']);
	unset($f[$key]);
	$fori++;
}

file_put_contents('cron.json', json_encode($f));

?>