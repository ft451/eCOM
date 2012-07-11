<?php
/**
 * The MobileOrders class implements module which shares informations
 * about new orders for smartphones.
 * 
 * @author Michal Kaczara <181132@student.pwr.wroc.pl>
 * @version 0.1
 */
class MobileOrders extends Module {
  
  /** 
   * @var string field for storing module settings page HTML code 
   */
  private $_html;
  
  /**
   * Plug-in class constructor
   * Sets basic settings and calls parent class constructor.
   */
  public function __construct(){
		$this->name = 'mobileorders';
		$this->displayName = $this->l('Mobile orders');
		$this->description = $this->l('Shares informations about new orders to your smartphone');
		$this->tab = 'administration';
		$this->version = 0.1;
		$this->author = 'Michal Kaczara';
		$this->need_instance = 0;

		parent::__construct();
  }
  
  /**
   * Method responsible for properly module install
   * The MO_PIN entry is added to shop configuration.
   * 
   * @return boolean result of install progress 
   */
  public function install(){
    if(!parent::install())
      return false;
    
    Configuration::updateValue('MO_PIN', '');
    return true;
  }
  
  /**
   * Method responsible for properly module uninstall
   * The MO_PIN entry is deleted from shop configuration.
   * 
   * @return boolean result of uninstall progress 
   */
  public function uninstall(){
    Configuration::deleteByName('MO_PIN');
    
    return parent::uninstall();
  }
  
  /**
   * Method gets the content of module settings page
   * 
   * @return string the HTML code of module settings page 
   */
  public function getContent(){
    $this->_html = '<h2>'.$this->displayName.'</h2>';
    
    if(!empty($_POST)){
      $this->_postValidation();
      
      if(!sizeof($this->_postErrors)){
        $this->_postProcess();
      }
      else{
        foreach($this->_postErrors AS $err){
          $this->_html .= $this->displayError($err);
        }
      }
    }
    
    $this->_displayForm();
    return $this->_html;
  }
  
  /**
   * Method generates the HTML code of module settings page
   */
  private function _displayForm(){
    $this->_html .= 
    '<fieldset>
      <legend>'.$this->l('Settings').'</legend>
      <form action="'.Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']).'" method="post">
        <label>'.$this->l('PIN number:').'</label>
        <div class="margin-form">
          <input type="text" name="pin" value="'.Tools::htmlentitiesUTF8(Tools::getValue('pin')).'" />
          <br />
          '.$this->l('The PIN number must contain 5 characters, includng at least one letter and one digit.').'
        </div>
        <br />
        <div class="margin-form"><input class="button" name="btnSubmit" value="'.$this->l('Update settings').'" type="submit" /></div>
      </form>
    </fieldset>
    ';
  }
  
  /**
   * Method processes POST request
   */
  private function _postProcess(){
    $pin = Configuration::get('MO_PIN');
    $newPin = Tools::getValue('pin');
    
    if($newPin != $pin){
//      if(file_exists('../'.$pin.'.php'))
//        unlink('../'.$pin.'.php');
//      
//      $hFile = fopen('../'.$newPin.'.php', 'w');
//      if($hFile !== false){
//        fwrite($hFile, 'BUM!');
//        fclose($hFile);
//      }
      
      Configuration::updateValue('MO_PIN', $newPin);
    }
    
    $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
  }
  
  /**
   * Method validates POST array values
   */
	private function _postValidation(){
		if (!Tools::getValue('pin'))
			$this->_postErrors[] = $this->l('PIN number is mandatory');
    else if(strlen(Tools::getValue('pin')) != 5)
      $this->_postErrors[] = $this->l('PIN number must contain exactly 5 characters');       
    else if(preg_match('/^\w*(?=\w*[0-9])(?=\w*[a-zA-Z])\w*$/', Tools::getValue('pin')) == null)
      $this->_postErrors[] = $this->l('PIN number must contain at least one letter and one didigt');      
	}
  
}

?>
