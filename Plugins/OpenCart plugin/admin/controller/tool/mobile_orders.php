<?php 
class ControllerToolMobileOrders extends Controller { 
	private $error = array();
	
	public function index() {		
		$this->load->language('tool/mobile_orders');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_clear'] = $this->language->get('button_clear');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['text_modify_pin'] = $this->language->get('text_modify_pin');
		$this->data['text_refresh_url'] = $this->language->get('text_refresh_url');
		$this->data['text_invalid_pin'] = $this->language->get('text_invalid_pin');
		$this->data['text_pins_equal'] = $this->language->get('text_pins_equal');
		$this->data['text_pin_not_set'] = $this->language->get('text_pin_not_set');
		$this->data['text_remove_pin'] = $this->language->get('text_remove_pin');
		$this->data['text_remove_pin_confirm'] = $this->language->get('text_remove_pin_confirm');
		$this->data['text_pin'] = $this->language->get('text_pin');
		$this->data['text_api'] = $this->language->get('text_api');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/mobile_orders', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		//$this->data['clear'] = $this->url->link('tool/mobile_orders/clear', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['refresh'] = $this->url->link('tool/mobile_orders/refresh', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['modify'] = $this->url->link('tool/mobile_orders/modify', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['remove'] = $this->url->link('tool/mobile_orders/remove', 'token=' . $this->session->data['token'], 'SSL');
		
		
		$file = DIR_LOGS . $this->config->get('config_error_filename');
		
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->load->model('tool/mobile_orders');
		$pin = $this->model_tool_mobile_orders->getPinCode();
		if($pin != "")
		{
			$this->data['pinCode'] = $pin;
			//$this->data['orderUrl'] = $this->model_tool_mobile_orders->getCurrentFileName().".php";
		}
		$this->template = 'tool/mobile_orders.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	public function modify() 
	{
		$this->load->language('tool/mobile_orders');
		if ($this->request->server['REQUEST_METHOD'] != 'POST' || !isset($this->request->post['newPinCode']))
		{
			$this->session->data['warning'] = $this->language->get('text_fraud');
			$this->redirect($this->url->link('tool/mobile_orders', 'token=' . $this->session->data['token'], 'SSL'));	
		}
	
		$newPin = $this->request->post['newPinCode'];
		if(preg_match("/[\w]{5}$/", $newPin) === 0 || strlen($newPin) != 5)
		{
			$this->session->data['warning'] = $this->language->get('text_invalid_pin');
			$this->redirect($this->url->link('tool/mobile_orders', 'token=' . $this->session->data['token'], 'SSL'));	
		}
		
		$this->load->model('tool/mobile_orders');
		$this->model_tool_mobile_orders->setPinCode($newPin);

		$this->session->data['success'] = $this->language->get('text_successfully_changed');
		$this->redirect($this->url->link('tool/mobile_orders', 'token=' . $this->session->data['token'], 'SSL'));		
	}
	
	
	public function remove()
	{
		$this->load->language('tool/mobile_orders');
		$this->load->model('tool/mobile_orders');
		if(!$this->model_tool_mobile_orders->isPinCodeSet())
			$this->session->data['warning'] = $this->language->get('text_pin_remove_error');
		else
		{
			$this->model_tool_mobile_orders->removePin();
			$this->session->data['success'] = $this->language->get('text_pin_removed');
		}
		$this->redirect($this->url->link('tool/mobile_orders', 'token=' . $this->session->data['token'], 'SSL'));		
	}
}
?>
