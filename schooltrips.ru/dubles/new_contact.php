<?php

file_put_contents('new_contact.log', chr(10).chr(10).json_encode($_REQUEST), FILE_APPEND);

//Если добавлен контакт
if($_REQUEST["contacts"]["add"]!=null){
    //Получим данные из хука
    $id=$_REQUEST["contacts"]["add"][0]["id"];
    $fields=$_REQUEST["contacts"]["add"][0]["custom_fields"];
    //Приведем данные к нужному виду
    $el = [];
    $el['id'] = $id;
    foreach ($fields as $field){
        if($field["id"]=='289000'){
            $el['email']=$field['values'][0]['value'];
        }
        if($field["id"]=='288998'){
            $el['phone']=$field['values'][0]['value'];
        }
    }
    //Перезапишем наш файл
    $file = json_decode(file_get_contents('all_con.json'),1);
    $file[$id] = $el;
    file_put_contents('all_con.json', json_encode($file));
}

//Если изменен контакт
if($_REQUEST["contacts"]["update"]!=null){
    die;
    //Получим данные из хука
    $id=$_REQUEST["contacts"]["update"][0]["id"];
    $fields=$_REQUEST["contacts"]["update"][0]["custom_fields"];
    //Приведем данные к нужному виду
    $el = [];
    $el['id'] = $id;
    foreach ($fields as $field){
        if($field["id"]=='289000'){
            $el['email']=$field['values'][0]['value'];
        }
        if($field["id"]=='288998'){
            $el['phone']=$field['values'][0]['value'];
        }
    }
    //Перезапишем наш файл
    $file = json_decode(file_get_contents('all_con.json'),1);
    $file[$id] = $el;
    file_put_contents('all_con.json', json_encode($file));
}

if($_REQUEST["contacts"]["delete"]!=null){
    //Получим данные из хука
    $id=$_REQUEST["contacts"]["delete"][0]["id"];
    //Приведем данные к нужному виду
    $el = [];
    $el['id'] = $id;
    //Перезапишем наш файл
    $file = json_decode(file_get_contents('all_con.json'),1);
    unset($file[$id]);
    file_put_contents('all_con.json', json_encode($file));
}


