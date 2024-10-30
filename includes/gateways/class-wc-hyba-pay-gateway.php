<?php

class WC_Hyba_Pay_Gateway extends WC_Hyba_Ipizza
{
    /**
     * WC_Hyba_Pay_Gateway
     */
    function __construct()
    {
        $this->id = 'hybapay';
        $this->method_title = __('Osta kohe, maksa hiljem. 0 € lisakulu!', 'wc-gateway-hyba');
        $this->method_description = __('HyBa PAY - osta kohe, maksa 60 päeva jooksul. Ilma ühegi lisatasuta!',
            'wc-gateway-hyba');
        $this->minimum_amount = 1;
        $this->vk_dest = WC_HYBA_LIVE . 'banklink/pay';

        $this->cart_banner = "või maksa alles siis, kui kaup käes. 0 € lisakulu!";
        $this->banner_minimum_amount = 1;
        $this->banner_maximum_amount = 99.99;

        parent::__construct();
    }
}
