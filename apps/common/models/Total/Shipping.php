<?php

namespace Stupycart\Common\Models\Total;

class Shipping  extends \Libs\Stupy\Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->hasShipping() && $this->session->has('shipping_method')) {
			$total_data[] = array( 
				'code'       => 'shipping',
        		'title'      => (($_tmp = $this->session->get('shipping_method')) ? $_tmp['title'] : null),
        		'text'       => $this->currency->format((($_tmp = $this->session->get('shipping_method')) ? $_tmp['cost'] : null)),
        		'value'      => (($_tmp = $this->session->get('shipping_method')) ? $_tmp['cost'] : null),
				'sort_order' => $this->config->get('shipping_sort_order')
			);

			if ((($_tmp = $this->session->get('shipping_method')) ? $_tmp['tax_class_id'] : null)) {
				$tax_rates = $this->tax->getRates((($_tmp = $this->session->get('shipping_method')) ? $_tmp['cost'] : null), (($_tmp = $this->session->get('shipping_method')) ? $_tmp['tax_class_id'] : null));
				
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
			
			$total += (($_tmp = $this->session->get('shipping_method')) ? $_tmp['cost'] : null);
		}			
	}
}
?>