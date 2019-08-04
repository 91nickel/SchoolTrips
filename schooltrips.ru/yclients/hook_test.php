<?php
header('Access-Control-Allow-Origin: *'); 
file_put_contents('hook_test.log', json_encode($_REQUEST), FILE_APPEND);
?>