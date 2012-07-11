var loggerUrl = "";//place your logger application URL here

function getPhoneInfo(url, operacja) {
	// MAC Address
	var networkInterface = {};
	networkInterface = window.plugins.macaddress.getMacAddress();
	var mac = networkInterface.mac;
	
	// IMEI
	var imei;
	window.plugins.imei.get(function(myImei) {
		imei = myImei;
    }, function() {
        console.log("fail");
    });
    
	var result = "operacja=" + escape(operacja)
	+ "&uuid=" + escape(device.uuid)
	+ "&url=" + escape(url)
	+ "&imei=" + escape(imei)
	+ "&mac=" + escape(mac);
	
	return result;
}
function logInfo(url, operacja) {
	$.get(loggerUrl + "?" + getPhoneInfo(url, operacja));
}