package pl.com.power.controller;

import javax.servlet.http.HttpServletRequest;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;

import pl.com.power.log.LogBo;
import pl.com.power.log.Log;
 
@Controller
@RequestMapping(value="/mobile")
public class LoggerMobileServlet {
        
    private LogBo logBo;
    
    @Autowired
    public void setLogBo(LogBo logBo) {
		this.logBo = logBo;
	}
 
    @RequestMapping(method=RequestMethod.GET)
    public void newLog(@RequestParam("operacja") String operacja,
    		@RequestParam("uuid") String uuid,
    		@RequestParam("url") String url,
    		@RequestParam("imei") String imei,
    		@RequestParam("mac") String mac,
    		HttpServletRequest request) {
    	
    	
        String ip = request.getRemoteAddr();

    	Log log = new Log(operacja, uuid, ip, url, imei, mac);
    	logBo.save(log);
    }
}
