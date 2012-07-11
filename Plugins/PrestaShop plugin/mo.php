<?php
define('MO', TRUE);
require_once(dirname(__FILE__).'/config/config.inc.php');
require_once(dirname(__FILE__).'/modules/mobileorders/FileManager.php');

$fileMan = new FileManager(dirname(__FILE__));

$fileMan->deleteOldFiles('_mo*');
$fileMan->createFile('_mo');

header("Content-type: application/json");
echo $_GET['jsoncallback'].'({"results":[{ "salt" : "'.$fileMan->getSalt().'"}]})';

$fileMan = null;
?>
