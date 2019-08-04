<?php
header('Access-Control-Allow-Origin: *');

$f = file_get_contents('list.json');
$f = json_decode($f,1);

$f[$_GET['id']] = json_decode($_GET['json']);

file_put_contents('list.json', json_encode($f));
?>