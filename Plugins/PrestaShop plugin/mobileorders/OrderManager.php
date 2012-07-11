<?php
if(!defined('MO'))
  exit;

/**
 * Description of OrderManager
 */
class OrderManager {

  private $_orders;
  private $_output;

  public function isPostAppRequest() {
    return $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mobile_app']) === true;
  }

  public function getLatestOrders() {
    $this->_output = array();
    $tmp = array();

    $this->_orders = $this->_getOrdersSummary();

    foreach ($this->_orders as $order) {
      $orderDetails = $this->_getOrderDetails($order["id_order"]);
      $currency = $this->_getOrderCurrency($order['id_currency']);
      $carrier = $this->_getOrderCarrier($order['id_carrier']);
      $customer = $this->_getCustomer($order["id_customer"]);
      $address = $this->_getCustomerInvoiceAddress(($order["id_customer"]));

      $tmp["order_id"] = $order["id_order"];
      $tmp["customer_name"] = $customer["firstname"]." ".$customer["lastname"];
      $tmp["customer_st_address"] = $address["address1"]."\n".$address["address2"];
      $tmp["customer_city"] = $address["city"];
      $tmp["customer_postcode"] = $address["postcode"];
      $tmp["customer_country"] = "";
      $tmp["customer_telephone"] = $address["phone"];
      $tmp["customer_email"] = $customer["email"];
      $tmp["delivery_address"] = "";
      $tmp["delivery_method"] = $carrier['name'];
      $tmp["payment_method"] = $order["payment"];
      $tmp["date_purchased"] = $order["date_add"];
      $tmp["order_status"] = "";
      $tmp["currency"] = $currency["iso_code"];
      $tmp["products"] = array();

      $tmpProducts = array();
      foreach ($orderDetails as $detail) {
        $tmpProducts["product_name"] = $detail["product_name"];
        $tmpProducts["product_price"] = round($detail["product_price"], 2);
        $tmpProducts["product_quantity"] = $detail["product_quantity"];

        $tmp["products"][] = $tmpProducts;
      }

      $tmp["final_price"] = round($order["total_paid"], 2);
      $tmp["additional_info"] = "";

      $this->_output[] = $tmp;
    }
  }

  public function getJSON() {
    return json_encode($this->_output);
  }

  private function _getOrdersSummary() {
    $sql = "SELECT * FROM "._DB_PREFIX_."orders WHERE date_add > DATE_SUB(NOW(), INTERVAL 3 DAY) LIMIT 100";
    $orders_summary = DB::getInstance()->ExecuteS($sql);
    return $orders_summary;
  }

  private function _getOrderDetails($cart_order_id) {
    $sql = "SELECT * FROM "._DB_PREFIX_."order_detail WHERE id_order=".$this->_mySQLSafe($cart_order_id);

    $order_details = DB::getInstance()->ExecuteS($sql);
    return $order_details;
  }

  private function _getCustomer($id) {
    $sql = "SELECT * FROM "._DB_PREFIX_."customer WHERE id_customer=".$this->_mySQLSafe($id);

    $res = DB::getInstance()->ExecuteS($sql);
    return $res[0];
  }

  private function _getCustomerInvoiceAddress($id) {
    $sql = "SELECT * FROM "._DB_PREFIX_."address WHERE id_address=".$this->_mySQLSafe($id);

    $res = DB::getInstance()->ExecuteS($sql);
    return $res[0];
  }

  private function _getOrderCurrency($currency_id) {
    $sql = "SELECT * FROM "._DB_PREFIX_."currency WHERE id_currency=".$this->_mySQLSafe($currency_id);

    $res = DB::getInstance()->ExecuteS($sql);
    return $res[0];
  }
    
  private function _getOrderCarrier($carrier_id){
    $sql = "SELECT * FROM "._DB_PREFIX_."carrier WHERE id_carrier=".$this->_mySQLSafe($carrier_id);

    $res = DB::getInstance()->ExecuteS($sql);
    return $res[0];
  }

  private function _mySQLSafe($value, $quote="'", $stripslashes = true) {
    // strip quotes if already in
    $value = str_replace(array("\'", "'"), "&#39;", $value);

    // Stripslashes 
    if (get_magic_quotes_gpc() && $stripslashes) {
      $value = stripslashes($value);
    }
    // Quote value
    if (version_compare(phpversion(), "4.3.0") == "-1") {
      $value = mysql_escape_string($value);
    }
    else {
      $value = mysql_real_escape_string($value);
    }
    $value = $quote.trim($value).$quote;

    return $value;
  }

}

?>
