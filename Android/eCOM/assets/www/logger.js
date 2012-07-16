var loggerUrl = "";//place your logger application URL here

function logInfo(url, operacja) {
	// MAC Address
	var networkInterface = {};
	networkInterface = window.plugins.macaddress.getMacAddress();
	var mac = networkInterface.mac;
	
	// IMEI
	window.plugins.imei.get(function(imei) {
		var result = "operacja=" + escape(operacja)
		+ "&uuid=" + escape(device.uuid)
		+ "&url=" + escape(url)
		+ "&imei=" + escape(imei)
		+ "&mac=" + escape(mac);
		$.get(loggerUrl + "?" + result);
    }, function() {
        console.log("fail");
        var result = "operacja=" + escape(operacja)
		+ "&uuid=" + escape(device.uuid)
		+ "&url=" + escape(url)
		+ "&imei="
		+ "&mac=" + escape(mac);
		$.get(loggerUrl + "?" + result);
    });
    
	
}