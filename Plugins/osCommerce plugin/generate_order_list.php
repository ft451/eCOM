<?php
require('includes/application_top.php');

function get_products($id) 
{
    $sql = "select * from orders_products where orders_id=".$id;
    $prod = tep_db_query($sql);
    return $prod;
}
function gather_data()
{
	$ini_file=parse_ini_file('admin/pin.ini');
	$pin = $ini_file['PIN'];
	tep_db_connect();
	$check_query = tep_db_query("select * from orders where orders.date_purchased > DATE_SUB(NOW(),INTERVAL 3 DAY)");
	$num_rows=tep_db_num_rows($check_query);
	if($num_rows>100)
	$num_rows=100;

	$json_data=array();
	for($i=0;$i<$num_rows;$i++)
    {
		$check_value = tep_db_fetch_array($check_query);

		
		$order['order_id']=$check_value['orders_id'];
		$order['customer_name']=$check_value['customers_name'];
		$order['customer_st_address']=$check_value['customers_street_address'];
		$order['customer_city']=$check_value['customers_city'];
		$order['customer_postcode']=$check_value['customers_postcode'];
		$order['customer_country']=$check_value['customers_country'];
		$order['customer_telephone']=$check_value['customers_telephone'];
		$order['customer_email']=$check_value['customers_email_address'];
		$order['delivery_address']=$check_value['delivery_name'].' '.$check_value['delivery_street_address'].' '.$check_value['delivery_postcode'].' '.$check_value['delivery_city'].' '.$check_value['delivery_state'].' '.$check_value['delivery_country'];
		$order['delivery_method']="";
		$order['payment_method']=$check_value['payment_method'];
		$order['date_purchased']=$check_value['date_purchased'];
		$order['currency']=$check_value['currency'];
		switch ($check_value['orders_status'])
		{
			case 1:
				$order['order_status']='Pending';
				break;
			case 2:
				$order['order_status']='Processing';
				break;
			case 3:
				$order['order_status']='Delivered';
				break;
		}
		
		$prod=get_products($order['order_id']);
		$order['products']=array();
		$tmp_prod=array();
		$num_rows2=tep_db_num_rows($prod);
		
		for($x=0;$x<$num_rows2;$x++)
		{
			$product=tep_db_fetch_array($prod);
			$tmp_prod['product_name']=$product['products_name'];
			$tmp_prod['product_price']=$product['products_price'];
			$tmp_prod['product_quantity']=$product['products_quantity'];
			$order['products'][]=$tmp_prod;
		}
		
		$order['final_price']=$check_value['final_price'];
		$order['additional_info']=$check_value['comments'];
	}	
		
		
	$json_data[]=$order;
		
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

gather_data();


?>