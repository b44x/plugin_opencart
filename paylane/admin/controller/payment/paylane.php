<?php
class ControllerPaymentPaylane extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/paylane');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paylane', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');

		$this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$this->data['entry_redirect_type'] = $this->language->get('entry_redirect_type');
		$this->data['entry_hash'] = $this->language->get('entry_hash');
		$this->data['entry_interface_lang'] = $this->language->get('entry_interface_lang');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant_id'])) {
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/paylane', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/paylane', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paylane_merchant_id'])) {
			$this->data['paylane_merchant_id'] = $this->request->post['paylane_merchant_id'];
		} else {
			$this->data['paylane_merchant_id'] = $this->config->get('paylane_merchant_id');
		}

		if (isset($this->request->post['paylane_redirect_type'])) {
			$this->data['paylane_redirect_type'] = $this->request->post['paylane_redirect_type'];
		} else {
			$this->data['paylane_redirect_type'] = $this->config->get('paylane_redirect_type');
		}

		if (isset($this->request->post['paylane_hash'])) {
			$this->data['paylane_hash'] = $this->request->post['paylane_hash'];
		} else {
			$this->data['paylane_hash'] = $this->config->get('paylane_hash');
		}

		if (isset($this->request->post['paylane_interface_lang'])) {
			$this->data['paylane_interface_lang'] = $this->request->post['paylane_interface_lang'];
		} else {
			$this->data['paylane_interface_lang'] = $this->config->get('paylane_interface_lang');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paylane_geo_zone_id'])) {
			$this->data['paylane_geo_zone_id'] = $this->request->post['paylane_geo_zone_id'];
		} else {
			$this->data['paylane_geo_zone_id'] = $this->config->get('paylane_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paylane_status'])) {
			$this->data['paylane_status'] = $this->request->post['paylane_status'];
		} else {
			$this->data['paylane_status'] = $this->config->get('paylane_status');
		}
		
		if (isset($this->request->post['paylane_sort_order'])) {
			$this->data['paylane_sort_order'] = $this->request->post['paylane_sort_order'];
		} else {
			$this->data['paylane_sort_order'] = $this->config->get('paylane_sort_order');
		}

		$this->template = 'payment/paylane.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paylane')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paylane_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>