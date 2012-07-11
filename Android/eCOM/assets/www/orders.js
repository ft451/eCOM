var allegroUrl = "";	//place URL to your Allegro PHP script here
var shoperUrl = "";		//place URL to your Shoper PHP script here
var shopifyUrl = "";	//place URL to your Shopify PHP script here
var shopifyApiKey = "";	//place your Shopify Api Key here
var filename = "";

function checkInternetConnection()
{
	if(navigator.network.connection.type==Connection.UNKNOWN || navigator.network.connection.type==Connection.NONE)
	{
		alert("You don't have Internet connection!");
		return false;
	}
	return true;
}
function generateShopifyLink()
{
	var name=document.getElementById('Shopifyaddress').value;
	document.getElementById('Shopifyregisterlink').innerHTML="<a href=\"https://"+name+"/admin/api/auth?api_key="+shopifyApiKey+"\" rel=\"external\">Click here to register your app</a>";
}
function openShopifyWindow()
{
	var name=document.getElementById('Shopifyaddress').value;
	window.open("https://"+name+"/admin/api/auth?api_key="+shopifyApiKey);
}
function loadOrderList(url, pin) {
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	var refresh = $("#refresh");	
	refresh.empty();
	refresh.hide();
	refresh.append('<a href="#order" onClick="loadOrderList('+"'"+url+"','"+pin+"'"+');" data-icon="refresh" class="ui-btn ui-btn-icon-right ui-btn-hover-a"><span class="ui-btn-inner"><span class="ui-btn-text">Refresh</span><span class="ui-icon ui-icon-gear ui-icon-shadow"></span></span></a>');
	
	if (checkInternetConnection()) {
		$('#spinner').show();
		
		//logger
		logInfo(url, "Checking orders");
		
		$.post(url+"/mo.php?jsoncallback=?", function(data){      
			filename = "_mo"+hex_md5(data.results[0].salt+pin)+".php";
			jsonForOrders(url, filename);
		}, "json");
	}
}

function jsonForOrders(url, filename) {
	$.post(url+"/"+filename+"/?jsoncallback=?", function(data){   
		saveOrders(data);      
	}, "json");
}

function loadOrderListShoper(url, pin){
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	var refresh = $("#refresh");	
	refresh.empty();
	refresh.hide();
	refresh.append('<a href="#order" onClick="loadOrderListShoper('+"'"+url+"','"+pin+"'"+');" data-icon="refresh" class="ui-btn ui-btn-icon-right ui-btn-hover-a"><span class="ui-btn-inner"><span class="ui-btn-text">Refresh</span><span class="ui-icon ui-icon-gear ui-icon-shadow"></span></span></a>');
	
		shoperData = pin.split(";", 2);
	
	if(shoperData.length != 2)
		return;
	
	if (checkInternetConnection()) {
		$('#spinner').show();
		
		//logger
		logInfo(url, "Checking orders");

		$.post(shoperUrl + "?h="+url+"&l="+shoperData[0]+"&p="+shoperData[1]+"&jsoncallback=?", function(data){
			saveOrders(data);
		}, "json");
	}
}

function saveOrders(data) {
	clearProducts();
	clearOrders();
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	$.each(data.results, function(i,position){  
		var id = position.order_id;
		
		addOrder(id, position.customer_name, position.customer_st_address, position.customer_city, position.customer_postcode,
				  position.customer_country, position.customer_telephone, position.customer_email, position.delivery_address,
				  position.delivery_method, position.payment_method, position.date_purchased, position.order_status, position.currency,
				  position.final_price, position.additional_info);
		
	     $.each(position.products, function(i2,position2){
	    	 addProduct(id, position2.product_name, position2.product_price, position2.product_quantity);
	     });
	});
	showOrders2();
}

function loadOrderList2(url, pin) {
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	var refresh = $("#refresh");	
	refresh.empty();
	refresh.hide();
	refresh.append('<a href="#order" onClick="loadOrderList2('+"'"+url+"','"+pin+"'"+');" data-icon="refresh" class="ui-btn ui-btn-icon-right ui-btn-hover-a"><span class="ui-btn-inner"><span class="ui-btn-text">Refresh</span><span class="ui-icon ui-icon-gear ui-icon-shadow"></span></span></a>');
		
	if (checkInternetConnection()) {
		$('#spinner').show();
		
		//logger
		logInfo(url, "Checking orders");
		
		$.get(url+"/generate_order_list.php", function(data){      
			filename = "_temp_mobile_"+hex_md5(pin+data)+".php";
			jsonForOrders2(url, filename);
		});
	}
}

function loadOrderList3(url, pin) {
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	var refresh = $("#refresh");	
	refresh.empty();
	refresh.hide();
	refresh.append('<a href="#order" onClick="loadOrderList3('+"'"+url+"','"+pin+"'"+');" data-icon="refresh" class="ui-btn ui-btn-icon-right ui-btn-hover-a"><span class="ui-btn-inner"><span class="ui-btn-text">Refresh</span><span class="ui-icon ui-icon-gear ui-icon-shadow"></span></span></a>');
		
	if (checkInternetConnection()) {
		$('#spinner').show();
		
		//logger
		logInfo(url, "Checking orders");
		
		$.post(shopifyUrl+"?shop="+url+"&key="+pin+"&jsoncallback=?",function(data){   
			saveOrders2(data);
		}, "json");
	}
}

function loadOrderListAllegro(login, password) {
	var ordersList = $("#orderContentList");
	ordersList.empty();
	
	var refresh = $("#refresh");	
	refresh.empty();
	refresh.hide();
	refresh.append('<a href="#order" onClick="loadOrderListAllegro('+"'"+login+"','"+password+"'"+');" data-icon="refresh" class="ui-btn ui-btn-icon-right ui-btn-hover-a"><span class="ui-btn-inner"><span class="ui-btn-text">Refresh</span><span class="ui-icon ui-icon-gear ui-icon-shadow"></span></span></a>');
	
	if (checkInternetConnection()) {
		$('#spinner').show();
		
		//logger
		logInfo("Allegro - " + login, "Checking orders");
	
		$.post(allegroUrl, { login: login, password: password }, function(data){
			saveOrdersAllegro(data);
		}, "json");
	}
}

function jsonForOrders2(url, filename) {
	$.post(url+"/"+filename+"/?jsoncallback=?", function(data){   
		saveOrders2(data);
	}, "json");
}

function saveOrders2(data) {
	clearProducts();
	clearOrders();
	
	$.each(data.result, function(i,position){   
		 addOrder(position.order_id, position.customer_name, position.customer_st_address, position.customer_city, position.customer_postcode,
				  position.customer_country, position.customer_telephone, position.customer_email, position.delivery_address,
				  position.delivery_method, position.payment_method, position.date_purchased, position.order_status, position.currency,
				  position.final_price, position.additional_info)
				  
	     $.each(position.products, function(i2,position2){
	    	 addProduct(position.order_id, position2.product_name, position2.product_price, position2.product_quantity)
	     });
	});
	
	showOrders2();
}

function saveOrdersAllegro(data) {
	clearProducts();
	clearOrders();
	var ordersList = $("#orderContentList");
	ordersList.empty();

	for(i=0; i<data.length; i++)
	{
		if(data[i].user_company != "") {
			var customerCompany = data[i].sent_to_data_company;
		}
		else {
			var customerCompany = 0;
		}
		
		if(data[i].user_phone != "") {
			var customerPhone = data[i].sent_to_data_phone;;
		}
		else {
			var customerPhone = 0;
		}
		
		var deliveryAddress = data[i].sent_to_data_st_address +", "+ data[i].sent_to_data_st_postcode+" "+data[i].sent_to_data_city +" ("+data[i].sent_to_data_country+")";
		
		addOrderAllegro(data[i].order_id, data[i].sent_to_data_name, data[i].sent_to_data_st_address, data[i].sent_to_data_city, data[i].sent_to_data_st_postcode, data[i].sent_to_data_country, customerPhone, data[i].user_email, deliveryAddress, data[i].sent_to_data_shipment, data[i].sent_to_data_pay_type, data[i].sent_to_data_created_date, data[i].title, "zl", data[i].amount, data[i].msg);
		addProduct(data[i].auction_id, data[i].title, data[i].it_amount, data[i].it_quantity)
		
	}
	showOrdersAllegro();
	ordersList.listview( "refresh" );
}