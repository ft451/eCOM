package pl.com.power.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.ModelAndView;

import pl.com.power.log.LogBo;
import pl.com.power.log.Log;
import pl.com.power.user.User;

import java.util.List;

@Controller
@RequestMapping(value="/logger")
public class LoggerServlet {
	
    private LogBo logBo;
	private User user;
    
    @Autowired
    public void setLogBo(LogBo logBo) {
		this.logBo = logBo;
	}
    
    @Autowired
    public void setUser(User user) {
		this.user = user;
	}
    
	@RequestMapping(method = RequestMethod.GET)
    public ModelAndView showLog(@RequestParam(value = "strona", required = false) Integer numerStrony) {
		if(user.getId() == null) {
			return new ModelAndView("redirect:/");
		} else {
			Boolean nastepna = true;
	    	Boolean poprzednia = true;
	    	int numerNastepnej = 1;
	    	int numerPoprzedniej = 0;
	    	int rozmiarStrony = 20;
	    	
	   	    if(numerStrony == null || numerStrony < 1) {
	   	    	numerStrony = 0;
	    		poprzednia = false;
	    	} else {
	    		numerPoprzedniej = numerStrony - 1;
	    		numerNastepnej = numerStrony + 1;
	    	}
	    	List<Log> wyniki = logBo.szukaj(numerStrony * rozmiarStrony, rozmiarStrony+1);
	    	
	    	if(wyniki.size() < rozmiarStrony+1) {
	    		nastepna = false;
	    	} else if (wyniki.size() == rozmiarStrony + 1) {
	    		wyniki.remove(rozmiarStrony);
	    	}
	    	
	    	ModelAndView mav = new ModelAndView("logger");
			mav.addObject("wyniki", wyniki);
			mav.addObject("nastepna", nastepna);
			mav.addObject("numerNastepnej", numerNastepnej);
			mav.addObject("poprzednia", poprzednia);
			mav.addObject("numerPoprzedniej", numerPoprzedniej);
			mav.addObject("user", user);
			return mav;
		}
    }
}
