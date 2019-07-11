<?php
class ControllerExtensionPaymentPaylane extends Controller
{
    private $__error = array();

    protected $_data = array();

    public function index()
    {
        $this->load->language('extension/payment/paylane');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] === 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('paylane', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		}

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_enabled']       = $this->language->get('text_enabled');
        $data['text_disabled']      = $this->language->get('text_disabled');
        $data['text_all_zones']     = $this->language->get('text_all_zones');
        $data['text_yes']           = $this->language->get('text_yes');
        $data['text_no']            = $this->language->get('text_no');
        $data['text_authorization'] = $this->language->get('text_authorization');
        $data['text_sale']          = $this->language->get('text_sale');
        $data['text_edit']          = $this->language->get('text_edit');

        $data['entry_merchant_id']    = $this->language->get('entry_merchant_id');
        $data['entry_redirect_type']  = $this->language->get('entry_redirect_type');
        $data['entry_hash']           = $this->language->get('entry_hash');
        $data['entry_interface_lang'] = $this->language->get('entry_interface_lang');
        $data['entry_geo_zone']       = $this->language->get('entry_geo_zone');
        $data['entry_status']         = $this->language->get('entry_status');
        $data['entry_sort_order']     = $this->language->get('entry_sort_order');

        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['merchant_id'])) {
            $data['error_merchant_id'] = $this->error['merchant_id'];
        } else {
            $data['error_merchant_id'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', '', true),
            'separator' => false,
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', '', true),
            'separator' => ' :: ',
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/payment/paylane', 'token=' . $this->session->data['token'], true),
            'separator' => ' :: ',
        );

        //links
        $data['action'] = $this->url->link('extension/payment/paylane', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

        if (isset($this->request->post['paylane_merchant_id'])) {
            $data['paylane_merchant_id'] = $this->request->post['paylane_merchant_id'];
        } else {
            $data['paylane_merchant_id'] = $this->config->get('paylane_merchant_id');
        }

        if (isset($this->request->post['paylane_redirect_type'])) {
            $data['paylane_redirect_type'] = $this->request->post['paylane_redirect_type'];
        } else {
            $data['paylane_redirect_type'] = $this->config->get('paylane_redirect_type');
        }

        if (isset($this->request->post['paylane_hash'])) {
            $data['paylane_hash'] = $this->request->post['paylane_hash'];
        } else {
            $data['paylane_hash'] = $this->config->get('paylane_hash');
        }

        if (isset($this->request->post['paylane_interface_lang'])) {
            $data['paylane_interface_lang'] = $this->request->post['paylane_interface_lang'];
        } else {
            $data['paylane_interface_lang'] = $this->config->get('paylane_interface_lang');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['paylane_geo_zone_id'])) {
            $data['paylane_geo_zone_id'] = $this->request->post['paylane_geo_zone_id'];
        } else {
            $data['paylane_geo_zone_id'] = $this->config->get('paylane_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['paylane_status'])) {
            $data['paylane_status'] = $this->request->post['paylane_status'];
        } else {
            $data['paylane_status'] = $this->config->get('paylane_status');
        }

        if (isset($this->request->post['paylane_sort_order'])) {
            $data['paylane_sort_order'] = $this->request->post['paylane_sort_order'];
        } else {
            $data['paylane_sort_order'] = $this->config->get('paylane_sort_order');
        }

/*

if (Front::$IS_OC2) {
$data['header'] = $this->load->controller('common/header');
$data['column_left'] = $this->load->controller('common/column_left');
$data['footer'] = $this->load->controller('common/footer');
} else {
$this->children = array(
'common/header',
'common/footer'
);
}
 */

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/paylane', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/paylane')) {
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
