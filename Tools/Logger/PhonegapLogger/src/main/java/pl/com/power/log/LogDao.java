package pl.com.power.log;

import java.util.List;

import org.hibernate.Criteria;
import org.hibernate.Session;
import org.hibernate.criterion.Order;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import pl.com.power.log.Log;

public class LogDao extends HibernateDaoSupport {
	
	public void save(Log log) {
		getHibernateTemplate().save(log);
	}
	
	public void update(Log log) {
		getHibernateTemplate().update(log);
	}
	
	public void delete(Log log) {
		getHibernateTemplate().delete(log);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajOperacja(String operacja) {
		return (List<Log>)getHibernateTemplate().find("from Log where operacja='?'", operacja);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajUuid(String uuid) {
		return (List<Log>)getHibernateTemplate().find("from Log where uuid='?'", uuid);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajIp(String ip) {
		return (List<Log>)getHibernateTemplate().find("from Log where ip='?'", ip);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajUrl(String url) {
		return (List<Log>)getHibernateTemplate().find("from Log where url='?'", url);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajImei(String imei) {
		return (List<Log>)getHibernateTemplate().find("from Log where imei='?'", imei);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukajMac(String mac) {
		return (List<Log>)getHibernateTemplate().find("from Log where mac='?'", mac);
	}
	
	@SuppressWarnings("unchecked")
	public List<Log> szukaj(Integer poczatek, Integer ilosc) {
		Criteria criteria = getSession().createCriteria(Log.class)
				.addOrder(Order.desc("data"));
				criteria.setFirstResult(poczatek);
				criteria.setMaxResults(ilosc);
				List<Log> itemList = criteria.list();
				return itemList;
	}

}
