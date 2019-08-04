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

//get all events to arr
//put into select

$list = [];
$offset = 0;
while(1)
{
	$part = m($amo, '/api/v2/catalog_elements?catalog_id=6687&limit_offset='.$offset.'&limit_rows=500');
	if(empty($part['_embedded']))
		break;

	$part = $part['_embedded']['items'];

	$offset+=500;
	$list = array_merge($list,$part);
}

$fut_list = [];

foreach ($list as $key => $value) {
	if(count($value['leads'])==0)
		continue;
	$f = false;
	$c = false;
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==572521)
			if(strtotime($value2['values'][0]['value'])+3600*24>time())
				$f = true;
		if($value2['id']==572529)
			$c = true;
	}
	if($f&&$c)
	{
		$leads = m($amo, '/api/v2/leads?id[]='.implode('&id[]=', $value['leads']['id']));
		$leads = $leads['_embedded']['items'];
		foreach ($leads as $key2 => $value2) {
			if(empty($value2['main_contact']['id']))
				continue;
			$contact = m($amo, '/api/v2/contacts?id='.$value2['main_contact']['id']);
			$contact = $contact['_embedded']['items'][0];
			$leads[$key2]['contact'] = $contact;
		}
		$value['leads'] = $leads;
		$fut_list[] = $value;
	}
}

//echo json_encode($fut_list[0]);

$arr = [];

foreach ($fut_list as $key => $value) {
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==572529)
			$arr[$value2['values'][0]['value']] = [];
	}
}

foreach ($fut_list as $key => $value) {
	$str = [];
	$cur = '';
	$str['name'] = $value['name'];
	$str['leads'] = $value['leads'];
	foreach ($value['custom_fields'] as $key2 => $value2) {
		if($value2['id']==572529)
			$cur = $value2['values'][0]['value'];
		if($value2['id']==572521)
			$str['date'] = $value2['values'][0]['value'];
		if($value2['id']==572531)
			$str['time'] = $value2['values'][0]['value'];
	}
	$arr[$cur][] = $str;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Списки</title>
	<!-- UIkit CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/css/uikit.min.css" />

	<!-- UIkit JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/uikit.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/uikit-icons.min.js"></script>
	<script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
	<script>
		var data;
		$(document).ready(function(){
			data = $('.data').html();
			data = $.parseJSON(data);

			$('#cur').html('<option selected>Куратор</option>');
			for(var i in data)
			{
				if(i!='')
					$('#cur').append('<option>'+i+'</option>');
			}

			$('#cur').change(function(){
				console.log($(this).val());
				$('#exc').html('<option selected>Экскурсия</option>');
				for(var i in data[$(this).val()])
				{
					var v = data[$(this).val()][i];
					$('#exc').append('<option>'+v.name+' / '+v.date+' '+v.time+'</option>');
				}
			})

			$('#exc').change(function(){
				var name = $(this).val().split(' / ')[0];
				var leads = [];
				for(var i in data[$('#cur').val()])
				{
					if(data[$('#cur').val()][i].name==name)
					{
						leads = data[$('#cur').val()][i].leads;
					}
				}
				$('table tbody').html('');
				for(var i in leads)
				{
					var c=0,
						a=0,
						tel='';
					for(var t in leads[i].custom_fields)
					{
						if(leads[i].custom_fields[t].id==103878)
							c = leads[i].custom_fields[t].values[0].value;
						if(leads[i].custom_fields[t].id==118758)
							a = leads[i].custom_fields[t].values[0].value;
					}
					for(var t in leads[i].contact.custom_fields)
					{
						if(leads[i].contact.custom_fields[t].name=='Телефон')
							tel = leads[i].contact.custom_fields[t].values[0].value;
					}
					$('table tbody').append('<tr><td>'+leads[i].contact.name+'</td><td>'+tel+'</td><td>'+c+'</td><td>'+a+'</td></tr>')
				}
			})
		})
	</script>
	<div class="uk-hidden data"><?=json_encode($arr)?></div>
	<div class="uk-container">
		<div class="uk-margin"></div>
		<div class="uk-card uk-margin uk-card-body uk-card-default">
			<h3 class="uk-card-title">Списки по экскурсиям</h3>
			<div class="uk-grid">
				<div class="uk-width-1-2">
					<select class="uk-select" id="cur"></select>
				</div>
				<div class="uk-width-1-2">
					<select class="uk-select" id="exc">
						<option selected>Экскурсия</option>
					</select>
				</div>
			</div>
		</div>
		<div class="uk-card uk-card-body uk-card-default uk-margin">
			<table class="uk-table uk-table-striped">
				<thead>
					<tr>
						<th>ФИО</th>
						<th>Телефон</th>
						<th>Детей</th>
						<th>Взрослых</th>
						<th>Дети контакта</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Алексей Иванов</td>
						<td>+79998887777</td>
						<td>10</td>
						<td>5</td>
						<td style="width: 330px;">
							<img src="ph.png" alt="" style="border-radius: 7px;">
						</td>
					</tr>
					<tr>
						<td>Алексей Иванов</td>
						<td>+79998887777</td>
						<td>10</td>
						<td>5</td>
						<td style="width: 330px;">
							<img src="ph.png" alt="" style="border-radius: 7px;">
						</td>
					</tr>
					<tr>
						<td>Алексей Иванов</td>
						<td>+79998887777</td>
						<td>10</td>
						<td>5</td>
						<td style="width: 330px;">
							<img src="ph.png" alt="" style="border-radius: 7px;">
						</td>
					</tr>
					<tr>
						<td>Алексей Иванов</td>
						<td>+79998887777</td>
						<td>10</td>
						<td>5</td>
						<td style="width: 330px;">
							<img src="ph.png" alt="" style="border-radius: 7px;">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>