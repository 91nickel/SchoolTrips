<?
header('Access-Control-Allow-Origin: *');
if(empty($_GET['id']))
	die;

$f = file_get_contents('data.json');
$f = json_decode($f,1);
$t = $f[$_GET['id']];

//file_put_contents('del.log', $_GET['id'].chr(10),FILE_APPEND);
//file_put_contents('del.log', $t.chr(10),FILE_APPEND);

echo $t."<br>";

$a = 1;
while($a)
{
	foreach ($f as $key => $value) {
		if($key==$_GET['id'])
		{
			unset($f[$key]);
		}
	}

	$a = false;
	foreach ($f as $key => $value) {
		if($key==$_GET['id'])
			$a = 1;
	}
}

$f = json_encode($f);
file_put_contents('data.json', $f);

die;

$f = file_get_contents('data_del.json');
$f = json_decode($f,1);
$f[$_GET['id']] = time();
$f = json_encode($f);
file_put_contents('data_del.json',$f);
?>