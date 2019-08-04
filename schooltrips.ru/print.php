<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Печать билета</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div style="text-align: center">
	<div style="margin: 50px; display: inline-block;background: #eee; padding: 20px; font-family: Arial">
		<h3><?=$_GET['title']?></h3>
		<img src="http://qrcoder.ru/code/?http%3A%2F%2Fnova-agency.ru%2Fauto%2Fschooltrip%2FQR.php%3Flead_id%3D<?=$_GET['ID']?>&4&0" alt="">
	</div>
	</div>
	<script>
		//window.print();
	</script>
</body>
</html>