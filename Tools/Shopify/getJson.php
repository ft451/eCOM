<?php 
    $Shopify_API_key = "";
    $fullurl="https://".$_GET['shop']."/admin/orders.json";
    $key=$_GET['key'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, $Shopify_API_key.":".$key);
    
    curl_setopt($ch, CURLOPT_URL, $fullurl);
    
    $returned = curl_exec($ch);
    
    curl_close ($ch);
    $data=json_decode($returned);
    
    $cnt=count($data->{'orders'});
    for($i=0;$i<$cnt;$i++)
    {
        $order['order_id']=$data->{'orders'}[$i]->{'order_number'};
        $order['customer_name']=$data->{'orders'}[$i]->{'billing_address'}->{'name'};
        $order['customer_st_address']=$data->{'orders'}[$i]->{'billing_address'}->{'address1'}." ".$data->{'orders'}[$i]->{'billing_address'}->{'address2'};
        $order['customer_city']=$data->{'orders'}[$i]->{'billing_address'}->{'city'};
        $order['customer_postcode']=$data->{'orders'}[$i]->{'billing_address'}->{'zip'};
        $order['customer_country']=$data->{'orders'}[$i]->{'billing_address'}->{'country_code'};
        $order['customer_telephone']=$data->{'orders'}[$i]->{'billing_address'}->{'phone'};
        $order['customer_email']=$data->{'orders'}[$i]->{'email'};
        $order['delivery_address']=$data->{'orders'}[$i]->{'shipping_address'}->{'name'}.", ".$data->{'orders'}[$i]->{'shipping_address'}->{'address1'}." ".$data->{'orders'}[$i]->{'shipping_address'}->{'address2'}.", ".
        $data->{'orders'}[$i]->{'shipping_address'}->{'zip'}." ".$data->{'orders'}[$i]->{'shipping_address'}->{'city'}." ".$data->{'orders'}[$i]->{'billing_address'}->{'country_code'};
        $order['delivery_method']=$data->{'orders'}[$i]->{'shipping_lines'}[0]->{'title'};
        $order['payment_method']="";
        $order['date_purchased']=$data->{'orders'}[$i]->{'created_at'};
        $order['order_status']='';
        $order['products']=array();
        $tmp_prod=array();
        
        for($j=0;$j<count($data->{'orders'}[$i]->{'line_items'});$j++)
        {
            $tmp_prod['product_name']=$data->{'orders'}[$i]->{'line_items'}[$j]->{'name'};
            $tmp_prod['product_price']=$data->{'orders'}[$i]->{'line_items'}[$j]->{'price'};
            $tmp_prod['product_quantity']=$data->{'orders'}[$i]->{'line_items'}[$j]->{'quantity'};
            $order['products'][]=$tmp_prod;
        }
        
        $order['final_price']=$data->{'orders'}[$i]->{'total_price'};
        $order['additional_info']=$data->{'orders'}[$i]->{'note'};
        
        
        $json_data[]=$order;
    }
    
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    echo $_GET['jsoncallback']."(".json_encode($json_data).");";
    
    ?>