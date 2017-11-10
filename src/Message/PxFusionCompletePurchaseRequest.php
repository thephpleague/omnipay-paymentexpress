<?php

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * PaymentExpress PxFusion Complete Purchase Request
 */
class PxFusionCompletePurchaseRequest extends PxFusionPurchaseRequest
{
    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getData()
    {
        $this->validate('sessionId');

        $data = new SimpleXMLElement('<GetTransaction/>');
        $data->addAttribute('xmlns', $this->namespace);
        $data->username = $this->getUsername();
        $data->password = $this->getPassword();
        $data->transactionId = $this->getSessionId();

        return $data;
    }

    protected function createResponse($data)
    {
        return $this->response = new PxFusionCompletePurchaseResponse($this, $data);
    }
}
