<?
header('Access-Control-Allow-Origin: *');  

if($_GET['type']=='contacts')
{
	$f = file_get_contents('cd.json');
	$f = json_decode($f,1);
	$f = array_reverse($f);
	echo json_encode($f);
}
?>