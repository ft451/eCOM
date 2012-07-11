package pl.com.power.log;

import java.util.List;

import pl.com.power.log.Log;
import pl.com.power.log.LogDao;

public class LogBo {
	
	LogDao logDao;
	
	public void setLogDao(LogDao logDao) {
		this.logDao = logDao;
	}

	public void save(Log log) {
		logDao.save(log);
	}
	
	public void update(Log log) {
		logDao.update(log);
	}
	
	public void delete(Log log) {
		logDao.delete(log);
	}
	
	public List<Log> szukajOperacja(String operacja) {
		return logDao.szukajOperacja(operacja);
	}
	
	public List<Log> szukajUuid(String uuid) {
		return logDao.szukajUuid(uuid);
	}
	
	public List<Log> szukajIp(String ip) {
		return logDao.szukajIp(ip);
	}
	
	public List<Log> szukajUrl(String url) {
		return logDao.szukajUrl(url);
	}
	
	public List<Log> szukajImei(String imei) {
		return logDao.szukajImei(imei);
	}
	
	public List<Log> szukajMac(String mac) {
		return logDao.szukajMac(mac);
	}
	
	public List<Log> szukaj(Integer poczatek, Integer ilosc) {
		return logDao.szukaj(poczatek, ilosc);
	}
}
