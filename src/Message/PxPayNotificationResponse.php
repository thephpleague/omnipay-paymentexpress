<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\NotificationInterface;

/**
 * PaymentExpress PxPay Notification (Webhook, Callback) Response
 */
class PxPayNotificationResponse extends Response implements NotificationInterface
{
    public function getTransactionStatus()
    {
        if ($this->isSuccessful()) {
            return static::STATUS_COMPLETED;
        }

        // NOTE: Windcave doesn't support static::STATUS_PENDING, which would have an additional check here

        return static::STATUS_FAILED;
    }
}
