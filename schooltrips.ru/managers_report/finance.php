<?
$mans = [];
$mans[1301478]='School Trip';
$mans[2650452]='Юлия';
$mans[2652066]='Евгения';
$mans[2652990]='Залина';
$mans[2653608]='Юлия Н';
$mans[2691033]='Светлана';
$mans[2745487]='Камилла';
$mans[2761263]='Роман';


if(isset($_GET['from']))
{
	$f = file_get_contents('https://script.google.com/macros/s/AKfycbxXiXjGnYRMFAWFau-wWaOdN4PvSL4HG9pDlK74G3OqfnbRGWQ/exec?doc=1Ym8XsD2o4TuLaxTwdFUin-MKf5MjUasdzcZ-P5KvswU&sheet=3');
	$f = json_decode($f,1);
	$res = [];
	$count = [];
	$total = 0;
	$total_c = 0;
	foreach ($f as $key => $value) {
		if($key==0)
			continue;
		$d = $value[0];
		$d = str_replace('.18', '.2018', $d);
		$d = strtotime($d);
		if((strtotime($_GET['from'])<=$d)&&(strtotime($_GET['to'])>=$d))
		{
			if(!isset($res[$value[3]]))
				$res[$value[3]] = 0;
			$res[$value[3]]+=$value[1];
			$count[$value[3]]++;
			$total+=$value[1];
			$total_c++;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Финансы</title>
</head>
<body>
	<form action="">
		<input type="text" name="from" value="<?=isset($_GET['from'])?$_GET['from']:'10.07.2018'?>">
		<input type="text" name="to" value="<?=isset($_GET['to'])?$_GET['to']:'20.07.2018'?>">
		<button>Показать</button>
	</form>
	<br>
	<table border="1px" cellspacing="0">
		<thead>
			<tr>
				<td>Менеджер</td>
				<td>Сумма</td>
				<td>Кол-во</td>
			</tr>
		</thead>
		<tbody>
			<?
			foreach ($res as $key => $value) {
				?><tr><td><?=isset($mans[$key])?$mans[$key]:$key?></td>
				<td><?=$value?></td><td><?=$count[$key]?></td></tr><?
			}
			?>
			<tr><td>Итого</td>
				<td><?=$total?></td><td><?=$total_c?></td></tr>
		</tbody>
	</table>

	<style>
		td{
			padding: 10px;
		}
	</style>
</body>
</html>