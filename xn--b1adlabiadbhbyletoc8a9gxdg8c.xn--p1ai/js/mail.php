<?php

if ($_POST) {


  

    $phone = htmlspecialchars($_POST["phone"]);
	$name = htmlspecialchars($_POST["name"]);

	$subject = 'Заявка с сайта';
    $skidka = '';

    include 'amocrm.php';




//    echo json_encode($json);
}
?>
