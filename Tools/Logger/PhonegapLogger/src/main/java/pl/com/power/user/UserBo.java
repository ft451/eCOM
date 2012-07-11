package pl.com.power.user;

import pl.com.power.user.User;
import pl.com.power.user.UserDao;

public class UserBo {
	
	UserDao userDao;
	
	public void setUserDao(UserDao userDao) {
		this.userDao = userDao;
	}

	public void save(User user) {
		userDao.save(user);
	}
	
	public void update(User user) {
		userDao.update(user);
	}
	
	public void delete(User user) {
		userDao.delete(user);
	}
	
	public User szukaj(String login, String password) {
		return userDao.szukaj(login, password);
	}
}
