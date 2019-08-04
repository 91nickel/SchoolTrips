<?
//header('Location: https://n113372.yclients.com/ ');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>School Trips - авторские экскурсии-квесты в музеях Москвы</title>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

	<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '1832160763697453');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	               src="https://www.facebook.com/tr?id=1832160763697453&ev=PageView&noscript=1"
		/></noscript>
	<!-- End Facebook Pixel Code -->
	
</head>
<body>
	<iframe src="/order/calendar4/<?=isset($_GET['link'])?($_GET['link'].'?nl'):(isset($_GET['exc'])?'#exc='.$_GET['exc']:'?nl')?><?=isset($_GET['userphone'])?('&userphone='.$_GET['userphone']):''?>" frameborder="0"></iframe>

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
				if(v.type_s=='Групповая')
					data.status_id=17475196;
				if(v.promo!='')
					data.promo = v.promo;
				$.get('/excursions/new.php',data,function(data){
					//счетчик карамели
					yaCounter46789143.reachGoal('ORDER');
					//счетчик st.ru
					yaCounter39632640.reachGoal('ORDER');
					console.log(data);
					window.location.href="/rfibank/url.php?skip=true&id="+data;
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

	<?php
	require_once '../order/calendar4/counters.php';
	?>

</body>
</html>