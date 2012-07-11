package pl.com.power.user;

import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import pl.com.power.user.User;

public class UserDao extends HibernateDaoSupport {

	public void save(User user) {
		getHibernateTemplate().save(user);
	}
	
	public void update(User user) {
		getHibernateTemplate().update(user);
	}
	
	public void delete(User user) {
		getHibernateTemplate().delete(user);
	}
	
	public User szukaj(String login, String password) {
		return (User)getHibernateTemplate().findByExample(new User(login, password)).get(0);
	}
}
