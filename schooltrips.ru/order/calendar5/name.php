<?
$f = json_decode(file_get_contents('names.json'),1);

if($_GET['meth']=='get')
{
	echo $f[$_GET['key']];
}

if($_GET['meth']=='set')
{
	$f[$_GET['key']] = $_GET['name'];
}

file_put_contents('names.json', json_encode($f));
?>