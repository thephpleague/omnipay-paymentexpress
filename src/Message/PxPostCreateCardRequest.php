<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * Windcave PxPost Create Credit Card Request
 */
class PxPostCreateCardRequest extends PxPostAuthorizeRequest
{
    public function getAction()
    {
        return $this->getParameter('action');
    }

    public function setAction($value)
    {
        return $this->setParameter('action', $value);
    }

    public function getData()
    {
        // don't use an existing card if we're trying to create a new one
        $this->setCardReference(null);

        $this->validate('card');
        $this->getCard()->validate();

        $this->setAmount($this->getAmount() ? $this->getAmount() : '1.00');

        if ($this->getAction()) {
            $this->action = $this->getAction();
        }

        $data = parent::getData();
        $data->EnableAddBillCard = 1;

        return $data;
    }
}
