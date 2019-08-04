<?
if(empty($_GET['id']))
	die;
file_put_contents('addtomess.log',date('d.m.Y H:i:s').chr(10).$_GET['id'].chr(10).chr(10),FILE_APPEND);
$f = file_get_contents('data.json');
//$arch = json_decode(file_get_contents('arch.json'),1);
$f = json_decode($f,1);
$time = time();
if(isset($_GET['time']))
	$time = $_GET['time'];
if(isset($f[$_GET['id']]))
	if($f[$_GET['id']]>$time)
		die;

$f[$_GET['id']] = $time;

//$arch[$_GET['id']] = time();
$f = json_encode($f);
file_put_contents('data.json',$f);
//file_put_contents('arch.json',json_encode($arch));
?>