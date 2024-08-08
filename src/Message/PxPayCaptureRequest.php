<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPay Capture Request
 */
class PxPayCaptureRequest extends PxPayAuthorizeRequest
{
    public function getAction()
    {
        return $this->getParameter('action');
    }

    public function setAction($value)
    {
        return $this->setParameter('action', $value);
    }

    public function getBillingId()
    {
        return $this->getParameter('billingId');
    }

    public function setBillingId($value)
    {
        return $this->setParameter('billingId', $value);
    }

    public function getRecurringMode()
    {
        return $this->getParameter('recurringMode');
    }

    public function setRecurringMode($value)
    {
        return $this->setParameter('recurringMode', $value);
    }

    public function getData()
    {
        $this->setAmount($this->getAmount() ? $this->getAmount() : '1.00');


        if ($this->getAction()) {
            $this->action = $this->getAction();
        }

        $data = parent::getData();

        $data->RecurringMode = $this->getRecurringMode();

        $data->BillingId = $this->getBillingId();

        return $data;
    }
}
