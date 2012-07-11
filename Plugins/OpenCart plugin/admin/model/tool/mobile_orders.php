<?php
class ModelToolMobileOrders extends Model {
	public function getPinCode() {
		$result = $this->db->query("SELECT `value` FROM `shop_config` WHERE `name` = \"pinCode\" LIMIT 1;")->row;
		return $result["value"]; //totest
	}
	
	public function isPinCodeSet()
	{
		$result = $this->db->query("SELECT `value` FROM `shop_config` WHERE `name` = \"pinCode\" LIMIT 1;")->row;
		return $result["value"] != "";
	}
	
	public function setPinCode($pinCode)	
	{
		//PinCode should be valid in this place.
		$this->db->query("UPDATE `shop_config` SET `value` = '".$this->db->escape($pinCode)."' WHERE `name` = 'pinCode';");
	}
	
	public function removePin()
	{
		$this->db->query("UPDATE `shop_config` SET `value` = '' WHERE `name` = 'pinCode';");
	}
}
?>