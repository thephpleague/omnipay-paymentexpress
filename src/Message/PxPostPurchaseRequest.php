<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * Windcave PxPost Purchase Request
 */
class PxPostPurchaseRequest extends PxPostAuthorizeRequest
{
    protected $action = 'Purchase';
}
