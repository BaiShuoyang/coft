<?php 

//testSMS.php

define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'\coft\SMSClass.php'); 

$sms = new NexmoMessage('5b030de9', '7e41727b');

$info = $sms->sendText( '+8618600961835', 'Austin Bai', 'Hello from Austin\'s server' );

echo $sms->displayOverview($info);

?>