<?php
class ControllerPaymentPaylane extends Controller
{
    const PAYLANE_SECURE_FORM_URL = 'https://secure.paylane.com/order/cart.html';

    protected function index()
    {
        $this->language->load('payment/paylane');

        $this->data['button_confirm']           = $this->language->get('button_confirm');
        $this->data['transaction_description'] = $this->language->get('text_description');

        $this->data['action'] = self::PAYLANE_SECURE_FORM_URL;

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info)
        {
            foreach ($this->cart->getProducts() as $product)
            {
                $this->data['transaction_description'] .= $product['name'] . ", ";
            }

            $this->data['transaction_description'] = substr($this->data['transaction_description'], 0, -2);

            $total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

            $this->data['merchant_id']     = $this->config->get('paylane_merchant_id');
            $this->data['language']        = $this->config->get('paylane_interface_lang');

            $this->data['merchant_transaction_id'] = str_pad((int)($this->session->data['order_id']), 4, "0", STR_PAD_LEFT);
            $this->data['back_url']                = $this->url->link('payment/paylane/callback', '', 'SSL');
            $this->data['amount']                  = (float)$order_info['total'];
            $this->data['transaction_type']        = "S";
            $this->data['currency_code']           = $order_info['currency_code'];
            $this->data['customer_name']           = html_entity_decode($order_info['payment_firstname'] . " " . $order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
            $this->data['customer_email']          = $order_info['email'];
            $this->data['customer_address']        = html_entity_decode($order_info['payment_address_1'] . (!empty($order_info['payment_address_2']) ? $order_info['payment_address_2'] : ""), ENT_QUOTES, 'UTF-8');
            $this->data['customer_city']           = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
            $this->data['customer_zip']            = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
            $this->data['customer_country']        = $order_info['payment_iso_code_2'];

            $this->data['hash'] = $this->generateHash(
                $this->data['merchant_transaction_id'],
                $this->data['amount'],
                $this->data['currency_code'],
                'S'
            );

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paylane.tpl'))
            {
                $this->template = $this->config->get('config_template') . '/template/payment/paylane.tpl';
            } else
            {
                $this->template = 'default/template/payment/paylane.tpl';
            }

            $this->render();
        }
    }

    public function callback()
    {
        $request = $this->request->{strtolower($this->config->get('paylane_redirect_type'))};

        if (isset($request['description']))
        {
            $order_id = (int)$request['description'];
        }
        else
        {
            $order_id = 0;
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info && $this->validateReturningHash($request))
        {
            $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
            if ($request['status'] == 'CLEARED' || $request['status'] == 'PERFORMED')
                $this->model_checkout_order->update($order_id, 2, "PayLane transaction ID: " . $request['id_sale']);
            else
                $this->model_checkout_order->update($order_id, 1, "PayLane transaction ID: " . $request['id_sale']);

            $this->redirect($this->url->link('checkout/success', '', 'SSL'));
        }
        else
        {
            if ( !empty($request['id_error']))
            {
                $this->log->write("In order " . $order_id . " Paylane returned error: " . $request['error_code'] . " - " . $request['error_text'] . ". You can find it under ID in merchant panel: " . $request['id_error']);
            }

            $this->data['order_id'] = $order_id;
            $this->data['error_code'] = $request['error_code'];
            $this->data['error_text'] = $request['error_text'];
            $this->data['back_link'] = $this->url->link('checkout/checkout', '', 'SSL');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paylane-error.tpl'))
            {
                $this->template = $this->config->get('config_template') . '/template/payment/paylane-error.tpl';
            } else
            {
                $this->template = 'default/template/payment/paylane-error.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );

            $this->response->setOutput($this->render());
        }
    }

    public function generateHash($m_t_id = null, $amount = null, $cur_code = null, $trans_type = null)
    {
        $hash = $this->config->get('paylane_hash');

        if (empty($hash))
        {
            return null;
        }

        return SHA1(
                $hash  . "|" .
                $m_t_id . "|" .
                $amount . "|" .
                $cur_code . "|" .
                $trans_type
        );
    }

    public function validateReturningHash($request)
    {
        $hash_transaction = SHA1(
                $this->config->get('paylane_hash') . "|" .
                $request['status'] . "|" .
                $request['description'] . "|" .
                $request['amount'] . "|" .
                $request['currency'] . "|" .
                $request['id_sale']
        );

        if ($hash_transaction == $request['hash'] && ( $request['status'] == 'CLEARED' || $request['status'] == 'PENDING' || $request['status'] == 'PERFORMED' ))
        {
            return true;
        }

        return false;
    }
}

?>
