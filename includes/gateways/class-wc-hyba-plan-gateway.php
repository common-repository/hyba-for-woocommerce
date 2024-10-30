<?php
class WC_Hyba_Plan_Gateway extends WC_Hyba_Ipizza {

	/**
	 * WC_Hyba_Plan_Gateway
	 */
	function __construct() {
		$this->id           = 'hybaplan';
		$this->method_title = __( 'Osta kohe, maksa osadena', 'wc-gateway-hyba' );
		$this->method_description = __( 'HyBa PLAN - osta kohe, maksa mitmes osas', 'wc-gateway-hyba' );
        $this->minimum_amount = 300;
        $this->vk_dest = WC_HYBA_LIVE . 'banklink/plan';

        $this->cart_banner = "või vali ise osamaksete arv. Maksed kuus al 9,52€.";
        $this->banner_minimum_amount = 10000;
        $this->banner_maximum_amount = 15000;

		parent::__construct();
	}
}
