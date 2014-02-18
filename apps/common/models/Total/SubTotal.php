<?php

namespace Stupycart\Common\Models\Total;

class SubTotal  extends \Libs\Stupy\Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/sub_total');
		
		$sub_total = $this->cart->getSubTotal();
		
		if ($this->session->has('vouchers') && $this->session->get('vouchers')) {
			foreach ($this->session->get('vouchers') as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}
		
		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
		$total += $sub_total;
	}
}
?>