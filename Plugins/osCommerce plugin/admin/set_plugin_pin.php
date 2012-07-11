<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['pin_update']) == true)
{
	//$parameters = array('PIN' => $_POST['mobilePIN']);
	$handle=fopen('pin.ini','w');
	fwrite($handle,'PIN = '.$_POST['mobilePIN']);
	fclose($handle);
	header('location:orders.php');
}
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['pin_cancel']) == true)
{
	header('location:orders.php');
}
function getPIN()
{
	$ini_file=parse_ini_file('pin.ini');
	return $ini_file['PIN'];
} 
?>
<p class="pageTitle">Your mobile plugin PIN number:</p>
<p>
	<span class="copyText">
	</span>
	<form action="" method="post">
		<div class="formRow">
			<label for="apiKey">PIN number:</label><input id="mobilePIN" type="text" name="mobilePIN" value="<?php echo getPIN(); ?>" class="text"/>
		</div>
		<div class="formRow">
			<input type="submit" value="Save new PIN" name="pin_update" />
			<input type="submit" value="Cancel" name="pin_cancel"/>
		</div>
	</form>
</p>