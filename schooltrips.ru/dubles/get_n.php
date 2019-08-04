<?
header('Access-Control-Allow-Origin: *');
//https://dmitrybondar.ru/auto/rosglobal/get_contacts.php
#Массив с параметрами, которые нужно передать методом POST к API системы

$contacts = json_decode(file_get_contents('all_con.json'),1);
echo count($contacts);

?>