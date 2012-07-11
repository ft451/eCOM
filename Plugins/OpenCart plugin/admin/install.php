<?php
		// Config
	require_once('config.php');
	   
	// Install 
	if (!defined('DIR_APPLICATION')) {
		header('Location: ../install/index.php');
		exit;
	}
	$errors = array();
	
	require_once(DIR_SYSTEM . 'mobile_orders/order_handler.php');

	try
	{
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	}catch(Exception $e){$errors[] = $e->what();}

	$head1;
	$head2;
	$head3;
	
	if(empty($errors))
	{
		try
		{
			$head1 = file_get_contents("./view/template/common/header.tpl");
			if(empty($head1))
				$errors[] = "Unable to load admin/view/template/common/header.tpl";		
		}catch(Exception $e){$errors[] = $e->what();}
	}
	if(empty($errors))
	{
		if(preg_match("~(<li>.*text_mobile_orders.*</li>)~iU", $head1) === 0)
		{
			if(preg_match("~(<li>.*text_backup.*</li>)~iU", $head1, $matches, PREG_OFFSET_CAPTURE) === 0)
				$errors[] = "Unable to find a hook in admin/view/template/common/header.tpl. File has been modified?";
			else
			{
				$tmp = substr($head1,0,$matches[0][1] + strlen($matches[0][0]) + 1);
				$tmp .= '
          <li><a href="<?php echo $mobile_orders; ?>"><?php echo $text_mobile_orders; ?></a></li>';
				$head1 = $tmp . substr($head1,$matches[0][1] + strlen($matches[0][0]) + 1);
				unset($tmp);
			}
			
		}
		else
			$errors[] = "Found mobile orders' management tag in admin/view/template/common/header.tpl. System already installed?";
	}
	if(empty($errors))
	{
		try
		{
			$head2 = file_get_contents("./controller/common/header.php");
			if(empty($head2))
				$errors[] = "Unable to load admin/controller/common/header.php";		
		}catch(Exception $e){$errors[] = $e->what();}
	}
	
	if(empty($errors))
	{
		if(preg_match("~(mobile_orders.*tool/mobile_orders.*;)~iU", $head2) === 0)
		{
			if(preg_match("~(module.*extension/module.*;)~iU", $head2, $matches, PREG_OFFSET_CAPTURE) === 0)
				$errors[] = "Unable to find a hook in admin/controller/common/header.php. File has been modified?";
			else
			{
				$tmp = substr($head2,0,$matches[0][1] + strlen($matches[0][0]) + 1);
				$tmp .= '			$this->data[\'mobile_orders\'] = $this->url->link(\'tool/mobile_orders\', \'token=\' . $this->session->data[\'token\'], \'SSL\');
			$this->data[\'text_mobile_orders\'] = $this->language->get(\'text_mobile_orders\'); 
';
				$head2 = $tmp . substr($head2,$matches[0][1] + strlen($matches[0][0]) + 1);
				unset($tmp);
			}
		}
		else
			$errors[] = "Found mobile orders' management tag in admin/controller/common/header.php. System already installed?";
	}
	
	if(empty($errors))
	{
		try
		{
			$head3 = file_get_contents("./language/english/common/header.php");
			if(empty($head3))
				$errors[] = "Unable to load admin//language/english/common/header.php";		
		}catch(Exception $e){$errors[] = $e->what();}
	}
	
	if(empty($errors))
	{
		if(preg_match("~(text_mobile_orders.*Mobile Management.*;)~iU", $head3) === 0)
		{
			if(preg_match("~(text_module.*Modules.*;)~iU", $head3, $matches, PREG_OFFSET_CAPTURE) === 0)
				$errors[] = "Unable to find a hook in admin//language/english/common/header.php. File has been modified?";
			else
			{
				$tmp = substr($head3,0,$matches[0][1] + strlen($matches[0][0]) + 1);
				$tmp .= '$_[\'text_mobile_orders\']				   = \'Mobile Management\';';
				$head3 = $tmp . substr($head3,$matches[0][1] + strlen($matches[0][0]) + 1);
				unset($tmp);
			}
		}
		else
			$errors[] = "Found mobile orders' management tag in admin/language/english/common/header.php. System already installed?";
	}
	if(empty($errors))
	{
		try
		{
			$db->query("CREATE TABLE `shop_config` (
			`name` VARCHAR( 255 ) NOT NULL DEFAULT '',
			`value` VARCHAR( 255 ) NOT NULL DEFAULT ''
			)");
			$db->query("INSERT INTO shop_config(name,value) VALUES ('pinCode', '');");
		}catch(Exception $e){$errors[] = $e->what();}
	}
	if(empty($errors))
	{
		file_put_contents("view/template/common/header.tpl",$head1);
		file_put_contents("controller/common/header.php",$head2);
		file_put_contents("language/english/common/header.php",$head3);
		echo("System successfully installed. Delete this file(admin/install.php).");
	}
	else
	{
		foreach($errors as $error)
			echo(">".$error."\n");
	}

?>