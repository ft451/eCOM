package pl.com.power.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.ModelAndView;


import pl.com.power.user.User;
import pl.com.power.user.UserBo;

@Controller
@RequestMapping(value="/index")
public class LoginServlet {
	private UserBo userBo;
	private User user;
	
	@Autowired
    public void setUserBo(UserBo userBo) {
		this.userBo = userBo;
	}
	
	@Autowired
    public void setUser(User user) {
		this.user = user;
	}
	@RequestMapping(method=RequestMethod.GET)
    public void index() {
	}

	@RequestMapping(method=RequestMethod.POST)
    public ModelAndView login(@RequestParam("inputlogin") String inputLogin, @RequestParam("inputpassword") String inputPassword) {
        
        Boolean loginError = false;
        String loginErrorMessage = "";
        Boolean passwordError = false;
        String passwordErrorMessage = "";
        ModelAndView result = new ModelAndView("index");
        if (user.getId() != null) {
        	return new ModelAndView("redirect:/logger");
        } else if (inputLogin.equals("")) {
        	loginError = true;
        	loginErrorMessage = "Podaj nazwe uzytkownika";
        	result.addObject("loginError", loginError);
        	result.addObject("loginErrorMessage", loginErrorMessage);
        } else if (inputPassword.equals("")) {
        	passwordError = true;
        	passwordErrorMessage = "Podaj haslo";
        	result.addObject("oldLogin", inputLogin);
        	result.addObject("passwordError", passwordError);
        	result.addObject("passwordErrorMessage", passwordErrorMessage);
        } else {
        	try {
        		User tempUser = userBo.szukaj(inputLogin, inputPassword);
        		user.setId(tempUser.getId());
        		user.setLogin(tempUser.getLogin());
        		user.setPassword(tempUser.getPassword());
        		return new ModelAndView("redirect:/logger");
        	} catch (Exception ex) {
        		passwordError = true;
        		loginError = true;
        		passwordErrorMessage = "i/lub haslo";
        		loginErrorMessage = "Nieprawidlowa nazwa uzytkownika";
        		result.addObject("oldLogin", inputLogin);
        		result.addObject("loginError", loginError);
        		result.addObject("passwordError", passwordError);
        		result.addObject("loginErrorMessage", loginErrorMessage);
        		result.addObject("passwordErrorMessage", passwordErrorMessage);
        	}
        }

		return result;
    }
}
