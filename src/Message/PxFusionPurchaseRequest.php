<?php

namespace Omnipay\PaymentExpress\Message;

use DOMDocument;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * PaymentExpress PxFusion Purchase Request
 */
class PxFusionPurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxf/pxf.svc';
    protected $namespace = 'http://paymentexpress.com';
    protected $action = 'Purchase';

    protected function getAddBillCard()
    {
        return 0;
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPxPostUsername()
    {
        return $this->getParameter('pxPostUsername');
    }

    public function setPxPostUsername($value)
    {
        return $this->setParameter('pxPostUsername', $value);
    }

    public function getPxPostPassword()
    {
        return $this->getParameter('pxPostPassword');
    }

    public function setPxPostPassword($value)
    {
        return $this->setParameter('pxPostPassword', $value);
    }

    public function getTxnRef()
    {
        return $this->getParameter('txnRef');
    }

    public function setTxnRef($value)
    {
        return $this->setParameter('txnRef', $value);
    }

    public function getData()
    {

        $this->validate('amount', 'currency', 'transactionId', 'returnUrl');

        $data = new SimpleXMLElement('<GetTransactionId/>');
        $data->addAttribute('xmlns', $this->namespace);
        $data->username = $this->getUsername();
        $data->password = $this->getPassword();

        $tranDetail = $data->addChild('tranDetail');
        $tranDetail->amount = $this->getAmount();
        $tranDetail->currency = $this->getCurrency();
        $tranDetail->enableAddBillCard = $this->getAddBillCard();
        $tranDetail->merchantReference = $this->getTransactionId();
        $tranDetail->returnUrl = $this->getReturnUrl();
        $tranDetail->txnType = $this->action;
        $tranDetail->txnRef = $this->getTransactionId();
        return $data;
    }

    public function sendData($data)
    {

        $document = new DOMDocument('1.0', 'utf-8');

        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope')
        );

        $body = $envelope->appendChild($document->createElement('soap:Body'));

        $body->appendChild($document->importNode(dom_import_simplexml($data), true));

        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => $this->namespace.'/IPxFusion/'.$data->getName(),
        );

        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();

        return $this->createResponse($httpResponse->getBody());
    }

    protected function createResponse($data)
    {
        return $this->response = new PxFusionPurchaseResponse($this, $data);
    }
}
