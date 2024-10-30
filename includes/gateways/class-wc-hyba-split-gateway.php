<?php

class WC_Hyba_Split_Gateway extends WC_Hyba_Ipizza
{
    /**
     * WC_Hyba_Split_Gateway
     */
    function __construct()
    {
        $this->id = 'hybasplit';
        $this->method_title = __('Osta kohe, maksa osadena. 0 € lisakulu!', 'wc-gateway-hyba');
        $this->method_description = __('HyBa SPLIT - osta kohe, maksad 3 või 6 osas. Ilma ühegi lisatasuta!',
            'wc-gateway-hyba');
        $this->minimum_amount = 100;

        $this->cart_banner = "või maksa 3 või 6 maksena. 0 € lisakulu!";
        $this->banner_minimum_amount = 100;
        $this->banner_maximum_amount = 1000;

        $this->vk_dest = WC_HYBA_LIVE . 'banklink/split';
        parent::__construct();
    }
}
