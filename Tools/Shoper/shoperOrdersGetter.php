<?php
$host = str_replace("http://", "", urldecode($_GET['h']));
$login = urldecode($_GET['l']);
$pass = urldecode($_GET['p']);

$getter = new ShoperOrdersGetter($host, $login, $pass);
$getter->getLatestOrders();
  
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
  
echo $_GET["jsoncallback"]."( {\"results\":".$getter->getJSON()."});";
  
$getter = null;
return;


/**
 * Klasa ShoperOrdersGetter
 */
class ShoperOrdersGetter{
  private $_conn;
  private $_sess;
  private $_output;
  
  /**
   * Konstruktor klasy pobieracza
   * 
   * @param string $host - adres sklepu, bez http://
   * @param string $login - login admina z dostêpem do API
   * @param string $password - has³o admina z dostêpem do API
   * @return boolean - FALSE - jeœli nie uda³o siê stworzyæ obiektu 
   */
  function __construct($host, $login, $password){
    $this->_conn = curl_init();
    curl_setopt($this->_conn, CURLOPT_URL, 'http://'.$host.'/webapi/json/');
    curl_setopt($this->_conn, CURLOPT_POST, true);
    curl_setopt($this->_conn, CURLOPT_RETURNTRANSFER, 1);
    
    $this->_sess = $this->_login($login, $password);
    if($this->_sess == null){
      return false;
    }
  }
  
  /**
   * Metoda odpowiedzialna za pobieranie zamówieñ
   * @return void
   */
  public function getLatestOrders() {
    if($this->_sess == null){
      return;
    }
    
    $this->_output = array();
    $tmp = array();
    
    $params = Array(
        "method" => "call",
        "params" => Array($this->_sess, "order.new.list", Array(true, true, Array(0))                )
    );
 
    curl_setopt($this->_conn, CURLOPT_POSTFIELDS, "json=" . json_encode($params));
    $result = (Array)json_decode(curl_exec($this->_conn));
 
    if (isset($result['error'])){
        $this->_output = null;
        return;
    } 
    else{
        $i = 0;
        $days3 = 60*60*24*3;
        foreach ($result as $item) {
          if($i == 99){
            break;
          }

          $order = (Array)$item;
          
          if(strtotime($order["date"]) < time() - $days3){
            continue;
          }

          $tmp["order_id"] = $order["order_id"];
          
          $billingAddress = (Array)$order['billingAddress'];
          $tmp["customer_name"] = $billingAddress["firstname"]." ".$billingAddress["lastname"];
          $tmp["customer_st_address"] = $billingAddress["street1"]."\n".$billingAddress["street2"];
          $tmp["customer_city"] = $billingAddress["city"];
          $tmp["customer_postcode"] = $billingAddress["postcode"];
          $tmp["customer_country"] = $billingAddress['country'];
          $tmp["customer_telephone"] = $billingAddress["phone"];
          $tmp["customer_email"] = $order["email"];
          
          $deliveryAddress = (Array)$order['billingAddress'];
          $tmp["delivery_address"] = $deliveryAddress["street1"]." ".$deliveryAddress["street2"];
          $tmp["delivery_method"] = "";
          $tmp["payment_method"] = $this->_getOrderPayment($order["payment_id"]);
          $tmp["date_purchased"] = $order["date"];
          $tmp["order_status"] = "";
          $tmp["currency"] = $this->_getOrderCurrency($order["currency_id"]);
          $tmp["products"] = array();
          
          $tmpProducts = array();
          $products = (Array)$order['products'];
          foreach ($products as $p) {
            $product = (Array)$p;
            $tmpProducts["product_name"] = $product["name"];
            $tmpProducts["product_price"] = $product["price"];
            $tmpProducts["product_quantity"] = $product["quantity"];

            $tmp["products"][] = $tmpProducts;
          }

          $tmp["final_price"] = $order["sum"];
          
          $this->_output[] = $tmp;
          $i++;
        }
    }
    
    $result = null;
    $params = null;
  }
  
  /**
   * Metoda zwraca zamówienia w formacie JSON
   * 
   * @return string lista zamówieñ w formacie JSON 
   */
  public function getJSON() {
    return json_encode($this->_output);
  }
  
  /**
   * Metoda odpowiedzialna za pobranie kodu ISO waluty zamówienia
   * 
   * @param type $currency_id - id waluty
   * @return string kod ISO waluty zamówienia 
   */
  private function _getOrderCurrency($currency_id) {
    $paramsCurr = Array(
        "method" => "call",
        "params" => Array($this->_sess, "currency.info", Array(intval($currency_id), true, false))
    );

    curl_setopt($this->_conn, CURLOPT_POSTFIELDS, "json=".json_encode($paramsCurr));
    $resultCurr = (Array) json_decode(curl_exec($this->_conn));

    $currency = "";
    if (!isset($resultCurr["error"])) {
      $currency = $resultCurr["name"];
    }
    
    $resultCurr = null;
    $paramsCurr = null;
    
    return $currency;
  }
  
  /**
   * Metoda odpowiedzialna za pobranie nazwy metody p³atnoœci zamówienia
   * 
   * @param type $payment_id - id metody p³atnoœci
   * @return string nazwa metody p³atnoœci zamówienia 
   */
  private function _getOrderPayment($payment_id) {
    $paramsPay = Array(
        "method" => "call",
        "params" => Array($this->_sess, "payment.info", Array(intval($payment_id), true, false))
    );

    curl_setopt($this->_conn, CURLOPT_POSTFIELDS, "json=".json_encode($paramsPay));
    $resultPay = (Array) json_decode(curl_exec($this->_conn));

    $payment_method = "";
    if (!isset($resultPay["error"])) {
      $translPay = (Array) $resultPay["translations"];
      $translPL = (Array) $translPay['pl_PL'];
      $payment_method = $translPL["title"];
    }
    
    $resultPay = null;
    //$postParamsPay = null;
    $paramsPay = null;
    
    return $payment_method;
  }
  
  /**
   * Metoda odpowiedzialna za wykonanie logowania do API
   * 
   * @param string $login - login admina
   * @param string $password - has³o admina
   * @return string identyfikator sesji API 
   */
  private function _login($login, $password) {
    $params = Array(
      "method" => "login",
      "params" => Array($login, $password)
    );
    curl_setopt($this->_conn, CURLOPT_POSTFIELDS, "json=" . json_encode($params));
    $result = (Array) json_decode(curl_exec($this->_conn));
    if (isset($result['error'])) {
        return null;
    } else {
        return $result[0];
    }
  }
  
  /**
   * Desktruktor klasy - koñczy po³¹czenie CURL
   */
  function __destruct(){
    curl_close($this->_conn);
  }
}
?>
