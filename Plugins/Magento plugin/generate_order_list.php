<?php
$host="";
$db_name="";
$username="";
$pass="";

define('MAGENTO_ROOT', getcwd());
require_once MAGENTO_ROOT . '/app/Mage.php';

function getDbData($file)
{
	$xml = simplexml_load_file($file);
	
	GLOBAL $host;
	GLOBAL $db_name;
	GLOBAL $username;
	GLOBAL $pass;
	GLOBAL $table_prefix;
	
	foreach($xml->children() as $child3)
	{
		foreach($child3->children() as $child2)
		{
			foreach($child2->children() as $child1)
			{
				foreach($child1->children() as $child0)
				{
					if ($child0->getName() == "table_prefix")
					{
						$table_prefix = $child0;
					}
					foreach($child0->children() as $child)
					{
				        if ($child->getName() == "host") 
				            $host = $child;
						if($child->getName() == "username")
							$username = $child;
						if($child->getName() == "password")
							$pass=$child;
						if($child->getName() == "dbname")
							$db_name=$child;
	  				}
	  			}
	  		}
	  	}
	}
}

function gather_data()
{
GLOBAL $host;
GLOBAL $db_name;
GLOBAL $username;
GLOBAL $pass;
GLOBAL $table_prefix;

	$ini_file=parse_ini_file('pin.ini');
	$pin = $ini_file['PIN'];
	$con = mysql_connect($host,$username,$pass);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  mysql_select_db($db_name, $con);
  $query="select * from ".$table_prefix."sales_flat_order where ".$table_prefix."sales_flat_order.created_at > DATE_SUB(NOW(),INTERVAL 3 DAY)";
  $result = mysql_query($query);
  $num_rows=mysql_num_rows($result);
  if($num_rows>100)
	$num_rows=100;
	
  $json_data=array();
  
for($i=0;$i<$num_rows;$i++)
    {
		$check_value = mysql_fetch_array($result);

		$order['order_id']=$check_value['entity_id'];
		$order['customer_name']=$check_value['customer_firstname']." ".$check_value['customer_lastname'];
		$order['customer_email']=$check_value['customer_email'];
		
		$query="select caet.value from ".$table_prefix."customer_address_entity as cae, ".
								$table_prefix."customer_address_entity_text as caet, ".
								$table_prefix."eav_attribute as attr ".
								"where cae.parent_id=".$check_value['customer_id']." and ".
								"cae.entity_id=caet.entity_id and ".
								"caet.entity_type_id=attr.entity_type_id and ".
								"caet.attribute_id=attr.attribute_id and ".
								"attr.attribute_code='street'";
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['customer_st_address']=$val['value'];
		
		$query="select caev.value from ".$table_prefix."customer_address_entity as cae, ".
								$table_prefix."customer_address_entity_varchar as caev, ".
								$table_prefix."eav_attribute as attr ".
								"where cae.parent_id=".$check_value['customer_id']." and ".
								"cae.entity_id=caev.entity_id and ".
								"caev.entity_type_id=attr.entity_type_id and ".
								"caev.attribute_id=attr.attribute_id and ".
								"attr.attribute_code='city'";
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['customer_city']=$val['value'];
		
		$query="select caev.value from ".$table_prefix."customer_address_entity as cae, ".
								$table_prefix."customer_address_entity_varchar as caev, ".
								$table_prefix."eav_attribute as attr ".
								"where cae.parent_id=".$check_value['customer_id']." and ".
								"cae.entity_id=caev.entity_id and ".
								"caev.entity_type_id=attr.entity_type_id and ".
								"caev.attribute_id=attr.attribute_id and ".
								"attr.attribute_code='postcode'";
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['customer_postcode']=$val['value'];
		
		$query="select caev.value from ".$table_prefix."customer_address_entity as cae, ".
								$table_prefix."customer_address_entity_varchar as caev, ".
								$table_prefix."eav_attribute as attr ".
								"where cae.parent_id=".$check_value['customer_id']." and ".
								"cae.entity_id=caev.entity_id and ".
								"caev.entity_type_id=attr.entity_type_id and ".
								"caev.attribute_id=attr.attribute_id and ".
								"attr.attribute_code='country_id'";
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['customer_country']=$val['value'];
		
		$query="select caev.value from ".$table_prefix."customer_address_entity as cae, ".
								$table_prefix."customer_address_entity_varchar as caev, ".
								$table_prefix."eav_attribute as attr ".
								"where cae.parent_id=".$check_value['customer_id']." and ".
								"cae.entity_id=caev.entity_id and ".
								"caev.entity_type_id=attr.entity_type_id and ".
								"caev.attribute_id=attr.attribute_id and ".
								"attr.attribute_code='telephone'";
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['customer_telephone']=$val['value'];
		
		$query="select * from ".$table_prefix."sales_flat_order_address where entity_id=".$check_value['shipping_address_id'];
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['delivery_address']=$val['firstname']." ".$val['lastname']." ".$val['street']." ".$val['postcode']." ".$val['city']." ".$val['region'];
		$order['delivery_method']=$check_value['shipping_description'];
		
		$query="select * from ".$table_prefix."sales_flat_order_payment where entity_id=".$check_value['entity_id'];
		$res=mysql_query($query);
		$val=mysql_fetch_array($res);
		$order['payment_method']=$val['method'];
		$order['date_purchased']=$check_value['created_at'];
		$order['order_status']=$check_value['status'];
		$order['currency']=$check_value['store_currency_code'];
		
		$query="select * from ".$table_prefix."sales_flat_order_item where parent_item_id IS NULL and order_id=".$check_value['entity_id'];
		$prod=mysql_query($query);
		$order['products']=array();
		$tmp_prod=array();
		$num_rows2=mysql_num_rows($prod);
		
		for($x=0;$x<$num_rows2;$x++)
		{
			$product=mysql_fetch_array($prod);
			$tmp_prod['product_name']=$product['name'];
			$tmp_prod['product_price']=$product['price'];
			$tmp_prod['product_quantity']=$product['qty_ordered'];
			$order['products'][]=$tmp_prod;
		}	
		$order['final_price']=$check_value['grand_total'];
		$order['additional_info']="";
			
		$json_data[]=$order;
	
	}
	mysql_close($con);
	
	$open = opendir('./');
	while( ($file = readdir($open)) !== false ) 
            {
            if ( strpos($file, '_temp_mobile_')!==false ) 
                {
                unlink($file);
                }
            }
    closedir($open);        
	
	srand();
	$challenge="";
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$length = 10;
	$challenge =substr(str_shuffle($chars),0,$length);
	$pin.=$challenge;
	$hash_pin=md5($pin);
	$handle=fopen('_temp_mobile_'.$hash_pin.".php",'w') or die('Can\'t create or open file');
	
	fwrite($handle,'<?php 
	header(\'Cache-Control: no-cache, must-revalidate\');
	header(\'Expires: Mon, 26 Jul 1997 05:00:00 GMT\');
	header(\'Content-type: application/json\');
	echo $_GET[\'jsoncallback\'] . \'( { "result":'. json_encode($json_data) . '});\'; ?>');
	fclose($handle);

	print( "$challenge");
}
getDbData("app/etc/local.xml");
gather_data();

?>