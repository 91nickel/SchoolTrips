<?
$type = 'status';
$time = time();
$id = $_REQUEST['leads']['status'][0]['id'];
if(isset($_REQUEST['leads']['add'][0]['id']))
	$id = $_REQUEST['leads']['add'][0]['id'];
if(isset($_REQUEST['leads']['chat'][0]['id']))
{
	$type = 'chat';
	$id = $_REQUEST['leads']['chat'][0]['id'];
}
if(isset($_REQUEST['leads']['note'][0]['note']))
{
	$time = strtotome($_REQUEST['leads']['note'][0]['note']['date_create']);
	$type = 'note';
	file_put_contents('hook.log',date('d.m.Y H:i:s').chr(10).'yes'.chr(10).chr(10),FILE_APPEND);
	if($_REQUEST['leads']['note'][0]['note']['note_type']!=102)
		die;
	$id = $_REQUEST['leads']['note'][0]['note']['element_id'];
	file_put_contents('hook.log',date('d.m.Y H:i:s').chr(10).json_encode($_REQUEST).chr(10).chr(10),FILE_APPEND);
}
file_put_contents('hook2.log',date('d.m.Y H:i:s').chr(10).$type.chr(10).chr(10),FILE_APPEND);
file_put_contents('hook2.log',date('d.m.Y H:i:s').chr(10).json_encode($_REQUEST).chr(10).chr(10),FILE_APPEND);
file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$id."&time=".$time);
?>