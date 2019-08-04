<?

$f = file_get_contents('finance/pays.json');
$f = json_decode($f,1);

$o = file_get_contents('pays_old.json');
$o = json_decode($o,1);

foreach ($f as $key => $value) {
	foreach ($o as $key2 => $value2) {
		if(strpos($value2['Номер заказа'], $value['id'])!==false)
		{
			$f[$key]['order_id'] = $value2['Номер заказа'];
			//echo $f[$key]['order_id']."<br>";
		}
	}
}

file_put_contents('finance/pays.json', json_encode($f));

?>