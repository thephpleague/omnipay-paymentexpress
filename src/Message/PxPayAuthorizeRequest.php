<?php

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPay Authorize Request
 */
class PxPayAuthorizeRequest extends AbstractRequest
{
    protected $endpoint = 'https://sec.paymentexpress.com/pxaccess/pxpay.aspx';
    protected $action = 'Auth';

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

    public function getTxnData1()
    {
        return $this->getParameter('txnData1');
    }

    public function setTxnData1($value)
    {
        return $this->setParameter('txnData1', $value);
    }

    public function getTxnData2()
    {
        return $this->getParameter('txnData2');
    }

    public function setTxnData2($value)
    {
        return $this->setParameter('txnData2', $value);
    }

    public function getTxnData3()
    {
        return $this->getParameter('txnData3');
    }

    public function setTxnData3($value)
    {
        return $this->setParameter('txnData3', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = new SimpleXMLElement('<GenerateRequest/>');
        $data->PxPayUserId = $this->getUsername();
        $data->PxPayKey = $this->getPassword();
        $data->TxnType = $this->action;
        $data->AmountInput = $this->getAmount();
        $data->CurrencyInput = $this->getCurrency();
        $data->MerchantReference = $this->getDescription();
        $data->TxnData1 = $this->getTxnData1();
        $data->TxnData2 = $this->getTxnData2();
        $data->TxnData3 = $this->getTxnData3();
        $data->UrlSuccess = $this->getReturnUrl();
        $data->UrlFail = $this->getReturnUrl();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->endpoint, null, $data->asXML())->send();

        return $this->createResponse($httpResponse->xml());
    }

    protected function createResponse($data)
    {
        return $this->response = new PxPayAuthorizeResponse($this, $data);
    }
}
