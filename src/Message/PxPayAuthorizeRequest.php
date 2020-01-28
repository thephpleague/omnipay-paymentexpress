<?php

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPay Authorize Request
 *
 * @link https://www.paymentexpress.com/Technical_Resources/Ecommerce_Hosted/PxPay_2_0
 */
class PxPayAuthorizeRequest extends AbstractRequest
{
    /**
     * PxPay Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://sec.paymentexpress.com/pxaccess/pxpay.aspx';

    /**
     * PxPay test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://uat.paymentexpress.com/pxaccess/pxpay.aspx';

    /**
     * PxPay TxnType
     *
     * @var string TxnType
     */
    protected $action = 'Auth';

    /**
     * Get the PxPay PxPayUserId
     *
     * Unique username to identify customer account.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the PxPay PxPayUserId
     *
     * @param string $value
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the PxPay PxPayKey
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the PxPay PxPayKey
     *
     * @param string $value
     * @return $this
     */
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

    public function getEndpoint()
    {
        return $this->getTestMode() === true ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Get the PxPay TxnData1
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return mixed
     */
    public function getTransactionData1()
    {
        return $this->getParameter('transactionData1');
    }

    /**
     * Set the PxPay TxnData1
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData1($value)
    {
        return $this->setParameter('transactionData1', $value);
    }

    /**
     * Get the PxPay TxnData2
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return mixed
     */
    public function getTransactionData2()
    {
        return $this->getParameter('transactionData2');
    }

    /**
     * Set the PxPay TxnData2
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData2($value)
    {
        return $this->setParameter('transactionData2', $value);
    }

    /**
     * Get the PxPay TxnData3
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return mixed
     */
    public function getTransactionData3()
    {
        return $this->getParameter('transactionData3');
    }

    /**
     * Set the TxnData3 field on the request
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData3($value)
    {
        return $this->setParameter('transactionData3', $value);
    }

    /**
     * Get the PxPay Opt
     *
     * Optional parameter can be used to set a timeout value for the hosted payments page
     * or block/allow specified card BIN ranges.
     *
     * @return mixed
     */
    public function getOpt()
    {
        return $this->getParameter('opt');
    }

    /**
     * Set the Opt field on the request
     *
     * @param string $value Max 64 bytes
     * @return $this
     */
    public function setOpt($value)
    {
        return $this->setParameter('opt', $value);
    }

    /**
     * Get the ForcePaymentMethod Opt
     *
     * Optional parameter can be used to set force a payment method for the hosted payments page
     * and ignore any other payment methods enabled on the account.
     *
     * @return mixed
     */
    public function getForcePaymentMethod()
    {
        return $this->getParameter('forcePaymentMethod');
    }

    /**
     * Set the ForcePaymentMethod field on the request
     *
     * @param string $value  The payment method to force e.g. 'Card', 'Account2Account', etc.
     *
     * @return mixed
     */
    public function setForcePaymentMethod($value)
    {
        return $this->setParameter('forcePaymentMethod', $value);
    }


    /**
     * Get the transaction data
     *
     * @return SimpleXMLElement
     */
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = new SimpleXMLElement('<GenerateRequest/>');
        $data->PxPayUserId = $this->getUsername();
        $data->PxPayKey = $this->getPassword();
        $data->TxnType = $this->action;
        $data->AmountInput = $this->getAmount();
        $data->CurrencyInput = $this->getCurrency();
        $data->UrlSuccess = $this->getReturnUrl();
        $data->UrlFail = $this->getCancelUrl() ?: $this->getReturnUrl();

        if ($this->getDescription()) {
            $data->MerchantReference = $this->getDescription();
        }

        if ($this->getTransactionId()) {
            $data->TxnId = $this->getTransactionId();
        }

        if ($this->getTransactionData1()) {
            $data->TxnData1 = $this->getTransactionData1();
        }

        if ($this->getTransactionData2()) {
            $data->TxnData2 = $this->getTransactionData2();
        }

        if ($this->getTransactionData3()) {
            $data->TxnData3 = $this->getTransactionData3();
        }

        if ($this->getCardReference()) {
            $data->DpsBillingId = $this->getCardReference();
        }

        if ($this->getOpt()) {
            $data->Opt = $this->getOpt();
        }

        if ($this->getForcePaymentMethod()) {
            $data->ForcePaymentMethod = $this->getForcePaymentMethod();
        }

        return $data;
    }

    /**
     * Send request
     *
     * @param  SimpleXMLElement $data
     * @return Omnipay\PaymentExpress\Message\PxPayAuthorizeResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data->asXML())->send();

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Create an authorize response
     *
     * @param  SimpleXMLElement $data
     * @return Omnipay\PaymentExpress\Message\PxPayAuthorizeResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new PxPayAuthorizeResponse($this, $data);
    }

    /**
     * Get the request return URL.
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return htmlentities($this->getParameter('returnUrl'));
    }
}
