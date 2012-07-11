<?php

class WebAPISoapClient extends SoapClient
{
    const COUNTRY_PL = 1;
	
    const QUERY_ALLEGROWEBAPI = 1;
    
    const API_KEY = ''; //klucz Allegro WebAPI

    public function __construct()
    {
        parent::__construct('http://webapi.allegro.pl/uploader.php?wsdl');
    }
}
	$config = array(
		'login' => $_POST['login'],
		'password' => $_POST['password'],
		'account_type' => 'sold',
		'apiKey' => WebAPISoapClient::API_KEY	
	);
	
	$country = WebAPISoapClient::COUNTRY_PL;
	
 try
	{
		$client = new WebAPISoapClient();
		
		$version = $client->doQuerySysStatus(WebAPISoapClient::QUERY_ALLEGROWEBAPI, $country, $config['apiKey']);
		
		$isCrypo = false;
		// czy sa dostepne funkcje kryptograficzne dla logowania
		if (function_exists('hash') && in_array('sha256', hash_algos()) )
		{
			$isCrypo = true;
			$password = hash('sha256', $config['password'], true);
 
		} // starszy mhash
		else if (function_exists('mhash') && is_int(MHASH_SHA256))
		{
			$isCrypo = true;
			$password = mhash(MHASH_SHA256, $config['password']);
		}
		
		if ($isCrypo)
		{
			$password = base64_encode($password);
			$session = $client->doLoginEnc($config['login'], $password, $country, $config['apiKey'], $version['ver-key']);
		}
		else
		{
			$session = $client->doLogin($config['login'], $config['password'], $country, $config['apiKey'], $version['ver-key']);
		}

		$count = $client->doMyAccountItemsCount($session['session-handle-part'], $config['account_type'], array());
		
		$auctions = $client->doMyAccount2($session['session-handle-part'], 'sold', 0, array() );
		
		foreach($auctions as $auction)
		{
			$ids[] = floatval($auction->{'my-account-array'}[0]);
		}
		
		$customers = $client->doGetPostBuyData($session['session-handle-part'], $ids );
		$transactionIDs = $client->doGetTransactionsIDs($session['session-handle-part'], $ids, 'seller');
		$dataForSellers = $client->doGetPostBuyFormsDataForSellers($session['session-handle-part'], $transactionIDs);
		
		$countries = $client->doGetCountries($country, $config['apiKey']);
		$shipment = $client->doGetShipmentData($country, $config['apiKey']);
		$states = $client->doGetStatesInfo($country, $config['apiKey']);
		
		foreach($customers as $customer)
		{
			// jako klucz obieramy ID aukcji i przypisujemy mu listê klientów
			$data[ $customer->{'item-id'} - PHP_INT_MAX] = $customer->{'users-post-buy-data'};
		}
		
		$json_data = array();
		$counter = 0;
		foreach($data as $id => $customers)
		{
			foreach($customers as $customer)
			{
				$order['it_quantity'] = $dataForSellers[$counter]->{'post-buy-form-items'}[0]->{'post-buy-form-it-quantity'};
				$order['it_amount'] = $dataForSellers[$counter]->{'post-buy-form-items'}[0]->{'post-buy-form-it-amount'};
				$order['auction_id'] = $dataForSellers[$counter]->{'post-buy-form-items'}[0]->{'post-buy-form-it-id'};
				$order['title'] = $dataForSellers[$counter]->{'post-buy-form-items'}[0]->{'post-buy-form-it-title'};
				
				$order['amount'] = $dataForSellers[$counter]->{'post-buy-form-amount'};
				$order['msg'] = $dataForSellers[$counter]->{'post-buy-form-msg-to-seller'};
				
				foreach($countries as $country2)
				{
					if($dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-country'} == $country2->{'country-id'})
					{
						$order['sent_to_data_country'] = $country2->{'country-name'};
						break;
					}
				}
				$order['sent_to_data_st_address'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-street'};
				$order['sent_to_data_st_postcode'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-postcode'};
				$order['sent_to_data_city'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-city'};
				$order['sent_to_data_name'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-full-name'};
				$order['sent_to_data_company'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-company'};
				$order['sent_to_data_phone'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-adr-phone'};
				
				$order['sent_to_data_pay_status'] = $dataForSellers[$counter]->{'post-buy-form-pay-status'};
				$order['sent_to_data_shipment'] = $shipment['shipment-data-list'][$dataForSellers[$counter]->{'post-buy-form-shipment-id'}-1]->{'shipment-name'};
				// trzeba sprawdzic, co sie dzieje dla punktu odbioru - moze byc, ze wowczas dane do wysylki sa puste i trzeba pobrac informacje o wysylce z post-buy-form-gd-address
				
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'm') $pay_type = 'mTransfer - mBank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'n') $pay_type = 'MultiTransfer - MultiBank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'w') $pay_type = 'BZWBK - Przelew24';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'o') $pay_type = 'Pekao24Przelew - Bank Pekao';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'i') $pay_type = 'P³acê z Inteligo';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'd') $pay_type = 'P³aæ z Nordea';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'p') $pay_type = 'P³aæ z iPKO';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'h') $pay_type = 'P³aæ z BPH';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'g') $pay_type = 'P³aæ z ING ';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'l') $pay_type = 'LUKAS e-przelew';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'u') $pay_type = 'Eurobank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'me') $pay_type = 'Meritum Bank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'ab') $pay_type = 'P³acê z Alior Bankiem';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wp') $pay_type = 'Przelew z Polbank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wm') $pay_type = 'Przelew z Millennium';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wk') $pay_type = 'Przelew z Kredyt Bank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wg') $pay_type = 'Przelew z BG¯ ';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wd') $pay_type = 'Przelew z Deutsche Bank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wr') $pay_type = 'Przelew z Raiffeisen Bank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wc') $pay_type = 'Przelew z Citibank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wn') $pay_type = 'Przelew z Invest Bank';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wi') $pay_type = 'Przelew z Getin Bank ';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wy') $pay_type = 'Przelew z Bankiem Pocztowym';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'c') $pay_type = 'Karta kredytowa';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'b') $pay_type = 'Przelew bankowy';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 't') $pay_type = 'P³atnoœæ testowa';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'collect_on_delivery') $pay_type = 'P³acê przy odbiorze';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'wire_transfer') $pay_type = 'Zwyk³y przelew - poza systemem P³acê z Allegro';
				if($dataForSellers[$counter]->{'post-buy-form-pay-type'} == 'not_specified') $pay_type = '0';
				
				$order['sent_to_data_pay_type'] = $pay_type;
				$order['sent_to_data_created_date'] = $dataForSellers[$counter]->{'post-buy-form-shipment-address'}->{'post-buy-form-created-date'};
				
				$order['order_id']=$id+PHP_INT_MAX;
				$order['user_login']=$customer->{'user-data'}->{'user-login'};
				foreach($states as $state2)
				{
					if($customer->{'user-data'}->{'user-state-id'} == $state2->{'state-id'})
					{
						$order['user_state_id'] = $state2->{'state-name'};
						break;
					}
				}
				$order['user_phone']=$customer->{'user-data'}->{'user-phone'};
				$order['user_email']=$customer->{'user-data'}->{'user-email'};
				
				$counter +=1;
				
				$json_data[]=$order;
			}
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json_data);
	}
	catch(SoapFault $soapFault)
	{
		echo 'Blad ', $soapFault->faultcode, ': ', $soapFault->faultstring, "\n";
	}
?>