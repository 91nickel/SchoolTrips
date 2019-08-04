<?

$csv = file_get_contents('csv.csv');

$csv = explode(chr(10), $csv);

$json = array();

foreach ($csv as $key => $value) {
	if($key==0)
		continue;
	$v = explode(';', $value);
	if($v[11]=='payed')
	{
		$s = array();
		$s['sum'] = $v[6];
		$s['id'] = explode('_',$v[16]);
		$s['id'] = $s['id'][0];
		$d = $v[1];
		$d = explode(' ', $d);
		$d[0] = explode('-', $d[0]);
		$d = $d[0][2].".".$d[0][1].".18".' '.$d[1];
		$s['date'] = $d;
		array_push($json, $s);
	}
}

$json = json_encode($json);

file_put_contents('pays.json', $json);

?>