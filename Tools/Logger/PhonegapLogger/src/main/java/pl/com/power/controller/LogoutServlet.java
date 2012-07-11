package pl.com.power.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.servlet.ModelAndView;


import pl.com.power.user.User;

@Controller
@RequestMapping(value="/logout")
public class LogoutServlet {
	private User user;
	
	@Autowired
    public void setUser(User user) {
		this.user = user;
	}
	
	@RequestMapping(method=RequestMethod.GET)
    public ModelAndView logout() {
		user.setId(null);
		user.setLogin(null);
		user.setPassword(null);
		return new ModelAndView("redirect:/");
	}
}
