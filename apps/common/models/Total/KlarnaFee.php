<?php

namespace Stupycart\Common\Models\Total;

class KlarnaFee  extends \Libs\Stupy\Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->language->load('total/klarna_fee');

		$status = true;
		
		$klarna_fee = $this->config->get('klarna_fee');
		
		if ($this->session->has('payment_address_id')) {
			$this->model_account_address = new \Stupycart\Common\Models\Account\Address();
			
			$address = $this->model_account_address->getAddress($this->session->get('payment_address_id'));
		} elseif ((($_tmp = $this->session->get('guest')) && isset($_tmp['payment']))) {
			$address = (($_tmp = $this->session->get('guest')) ? $_tmp['payment'] : null);
		}
		
		if (!isset($address)) {
			$status = false;
		} elseif (!(($_tmp = $this->session->get('payment_method')) && isset($_tmp['code'])) || (($_tmp = $this->session->get('payment_method')) ? $_tmp['code'] : null) != 'klarna_invoice') {
			$status = false;
		} elseif (!isset($klarna_fee[$address['iso_code_3']])) {
			$status = false;
		} elseif (!$klarna_fee[$address['iso_code_3']]['status']) {
			$status = false;
		} elseif ($this->cart->getSubTotal() >= $klarna_fee[$address['iso_code_3']]['total']) {
			$status = false;
		}
		
        if ($status) {
			$total_data[] = array(
				'code'       => 'klarna_fee',
				'title'      => $this->language->get('text_klarna_fee'),
				'text'       => $this->currency->format($klarna_fee[$address['iso_code_3']]['fee']),
				'value'      => $klarna_fee[$address['iso_code_3']]['fee'],
				'sort_order' => $klarna_fee[$address['iso_code_3']]['sort_order']
			);
			
			$tax_rates = $this->tax->getRates($klarna_fee[$address['iso_code_3']]['fee'], $klarna_fee[$address['iso_code_3']]['tax_class_id']);
			
			foreach ($tax_rates as $tax_rate) {
				if (!isset($taxes[$tax_rate['tax_rate_id']])) {
					$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
				} else {
					$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
				}
			}
			
			$total += $klarna_fee[$address['iso_code_3']]['fee'];
        }
    }
}
?>
