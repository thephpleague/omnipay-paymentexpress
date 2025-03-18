<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPay Notification (Webhook, Callback) Request
 */
class PxPayNotificationRequest extends PxPayCompleteAuthorizeRequest
{
    protected function createResponse($data)
    {
        return $this->response = new PxPayNotificationResponse($this, simplexml_load_string($data));
    }
}
