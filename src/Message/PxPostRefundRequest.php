<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * Windcave PxPost Refund Request
 */
class PxPostRefundRequest extends PxPostCaptureRequest
{
    protected $action = 'Refund';
}
