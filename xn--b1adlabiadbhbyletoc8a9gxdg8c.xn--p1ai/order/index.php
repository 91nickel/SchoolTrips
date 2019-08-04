<?
//header('Location: https://n113372.yclients.com/ ');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Запись на экскурсию</title>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
</head>
<body>
	<iframe src="http://nova-agency.ru/auto/schooltrip/order/calendar4/<?=isset($_GET['link'])?($_GET['link']):'#exc='.$_GET['exc']?>" frameborder="0"></iframe>

	<style>
		iframe{
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		.text{
			position: absolute;
			top: 40%;
			left: 0;
			width: 100%;
			text-align: center;
			color: #333;
			font-size: 20px;
			font-weight: bold;
			font-family: Arial;
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<script>
		window.addEventListener('message', function(event) {
			//console.log(event.data);
			if(event.data.name=='order')
			{
				var v = event.data.val;
				$('iframe').remove();
				console.log(v);
				$('body').append('<p class="text">Формируется заказ</p>');
				var data = {name:v.name,tel:v.tel,email:v.email,p1:v.k1,p2:v.k2,price:v.sale,cat_id:v.id,count:v.count};
				if(v.k1==undefined)
					data.status_id=20310937;
				if(v.data=='abon')
					data.status_id=20661493;
				$.get('http://nova-agency.ru/auto/schooltrip/excursions/new.php',data,function(data){
					console.log(data);
					window.location.href="http://nova-agency.ru/auto/schooltrip/rfibank/url.php?id="+data;
				});
			}
			if(event.data.name=='scroll')
			{
				window.scrollTo(0,0);
			}
		})

		$(document).ready(function(){
			//$('body').style('height',window.screen.availHeight);
		})
	</script>
</body>
</html>