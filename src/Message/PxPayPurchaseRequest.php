<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * Windcave PxPay Purchase Request
 */
class PxPayPurchaseRequest extends PxPayAuthorizeRequest
{
    protected $action = 'Purchase';
}
