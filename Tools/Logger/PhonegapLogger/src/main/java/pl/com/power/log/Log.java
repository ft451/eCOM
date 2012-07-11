package pl.com.power.log;

import java.io.Serializable;
import java.sql.Timestamp;
import java.util.Calendar;

public class Log implements Serializable {

	private static final long serialVersionUID = 1L;

	private Long id;
	private Timestamp data;
	private String operacja;
	private String uuid;
	private String ip;
	private String url;
	private String imei;
	private String mac;

	public Log() {
	}

	public Log(String nowaOperacja, String noweUuid, String noweIp, String nowyUrl, String nowyImei, String nowyMac) {
		this.data = new java.sql.Timestamp(Calendar.getInstance().getTime().getTime());
		this.operacja = nowaOperacja;
		this.uuid = noweUuid;
		this.ip = noweIp;
		this.url = nowyUrl;
		this.imei = nowyImei;
		this.mac = nowyMac;
	}

	public Long getId() {
		return this.id;
	}

	public void setId(Long noweId) {
		this.id = noweId;
	}

	public Timestamp getData() {
		return this.data;
	}

	public void setData(Timestamp nowaData) {
		this.data = nowaData;
	}

	public String getOperacja() {
		return this.operacja;
	}

	public void setOperacja(String nowaOperacja) {
		this.operacja = nowaOperacja;
	}
	
	public String getUuid() {
		return this.uuid;
	}

	public void setUuid(String noweUuid) {
		this.uuid = noweUuid;
	}
	
	public String getIp() {
		return this.ip;
	}

	public void setIp(String noweIp) {
		this.ip = noweIp;
	}
	
	public String getUrl() {
		return this.url;
	}

	public void setUrl(String nowyUrl) {
		this.url = nowyUrl;
	}
	
	public String getImei() {
		return this.imei;
	}

	public void setImei(String nowyImei) {
		this.imei = nowyImei;
	}
	
	public String getMac() {
		return this.mac;
	}

	public void setMac(String nowyMac) {
		this.mac = nowyMac;
	}

	@Override
	public String toString() {
		StringBuffer wynik = new StringBuffer("Wpis dziennika [");
		wynik.append(id).append(", ");
		wynik.append(data).append(", ");
		wynik.append(operacja).append(", ");
		wynik.append(uuid).append(", ");
		wynik.append(ip).append(", ");
		wynik.append(url).append(", ");
		wynik.append(imei).append(", ");
		wynik.append(mac).append("]");
		
		return wynik.toString();
	}

	
}
