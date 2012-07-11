<?php
if(!defined('MO'))
  exit;

/**
 * Description of FileManager
 */
class FileManager {

  private $_dir;
  private $_salt;

  public function FileManager($dir) {
    $this->_dir = $dir;
  }

  public function deleteOldFiles($pattern) {
    $files = glob($this->_dir.'/'.$pattern);
    if (count($files) > 0) {
      foreach ($files as $file) {
        unlink($file);
      }
    }
  }

  public function createFile($namePart) {
    $this->_salt = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPRSTUWXYZabcdefghijklmnopqrstuvwxyz'), 0, 10);
    $pin = Configuration::get('MO_PIN');

    $filename = md5($this->_salt.$pin);

    $hFile = fopen($this->_dir.'/'.$namePart.$filename.'.php', 'w');
    if ($hFile !== false) {
      fwrite($hFile, $this->_getTemplate());
      fclose($hFile);
    }
  }

  public function getSalt() {
    return $this->_salt;
  }

  private function _getTemplate() {
    return
            '<?php
            define("MO", TRUE);
            require_once(dirname(__FILE__)."/config/config.inc.php");
            require_once(dirname(__FILE__)."/modules/mobileorders/OrderManager.php");
   
            $orderMan = new OrderManager();
            
            //if(!$orderMan->isPostAppRequest())
            //  exit;
              
            $orderMan->getLatestOrders();
            
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Content-type: application/json");
            
            echo $_GET["jsoncallback"]."( {\"results\":".$orderMan->getJSON()."});";
           
            $orderMan = null;
           ?>
          ';
  }

}

?>
