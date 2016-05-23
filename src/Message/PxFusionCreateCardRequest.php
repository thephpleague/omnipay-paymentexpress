<?php

namespace Omnipay\PaymentExpress\Message;

/**
 * PaymentExpress PxPost Create Credit Card Request
 */
class PxFusionCreateCardRequest extends PxFusionPurchaseRequest
{
  /**
   * Get value for recurring field.
   *
   * @return bool
   */
    protected function getAddBillCard()
    {
        return 1;
    }

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
        $this->setAmount($this->getAmount() ? $this->getAmount() : '1.00');


        if ($this->getAction()) {
            $this->action = $this->getAction();
        }

        $data = parent::getData();

        return $data;
    }
}
