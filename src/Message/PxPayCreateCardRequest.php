<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Create Credit Card Request
 */
class PxPayCreateCardRequest extends PxPayAuthorizeRequest
{
    public function getData()
    {
        $this->setAmount($this->getAmount() ? $this->getAmount() : '1.00');
        
        $data = parent::getData();
        $data->EnableAddBillCard = 1;

        return $data;
    }


}
