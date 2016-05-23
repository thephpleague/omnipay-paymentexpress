<?php

namespace Omnipay\PaymentExpress\Message;

use DomDocument;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentExpress PxFusion Purchase Response
 */
class PxFusionCompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $responseDom = new DOMDocument;
        $responseDom->loadXML($data);

        $this->data = simplexml_import_dom($responseDom->documentElement->firstChild->firstChild->firstChild);
    }

    public function isSuccessful()
    {
        return $this->getCode() === 0;
    }

    public function getMessage()
    {
        return (string) $this->data->responseText;
    }

    public function getCode()
    {
        return (int) $this->data->status;
    }

    public function getTransactionReference()
    {
        return (string) $this->data->dpsTxnRef;
    }

    public function getTransactionId()
    {
        return (string) $this->data->merchantReference;
    }

    public function getCardReference()
    {
        return (string) $this->data->dpsBillingId;
    }
}
