<?php
	require_once('config.php');
	if (!defined('DIR_APPLICATION'))
		exit;	
	require_once(DIR_SYSTEM . 'mobile_orders/order_handler.php');

	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
	$pinCodeHandler = new PinCodeHandler($db);
	$pinCode = $pinCodeHandler->getPinCode();
	
	$salt = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPRSTUWXYZabcdefghijklmnopqrstuvwxyz'), 0, 10);
	$token = md5($salt.$pinCode);
	$file = "_mo$token.php"; //'_mo'.sha1($salt.$pinCode).'.php' //!!
	
	removeOldFiles();
	createNewContentFile($file,$salt, $token);
	
	header("Content-type: application/json");
	echo ($_GET['jsoncallback'].'({"results":[{ "salt" : "'.$salt.'"}]})');
?>