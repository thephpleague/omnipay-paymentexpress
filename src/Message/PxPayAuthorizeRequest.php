<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * PaymentExpress PxPay Authorize Request
 *
 * @link https://www.windcave.com/developer-e-commerce-paymentexpress-hosted-pxpay
 */
class PxPayAuthorizeRequest extends AbstractRequest
{
    /**
     * PxPay Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://sec.windcave.com/pxaccess/pxpay.aspx';

    /**
     * PxPay test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://uat.windcave.com/pxaccess/pxpay.aspx';

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
     * Get the PxPay EmailAddress
     *
     * Optional: The EmailAddress field can be used to store a customer's email address and will be returned in the
     * transaction response. The response data along with the email address can then be used by the merchant
     * to generate a notification/receipt email for the customer.
     *
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->getParameter('emailAddress');
    }

    /**
     * Set the PxPay EmailAddress
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setEmailAddress($value)
    {
        return $this->setParameter('emailAddress', $value);
    }

    /**
     * A timeout (TO) can be set for the hosted payments page, after which the payment page will
     * timeout and no longer allow a payment to be taken. Note: The default timeout of the created hosted
     * payment page session is 72 hours. A specific timeout timestamp can also be specified via the request in
     * Coordinated Universal Time (UTC). The value must be in the format "TO=yymmddHHmm" 
     * e.g.“TO=1010142221” for 2010 October 14th 10:21pm.
     * The merchant should submit the timeout value of when the payment page should timeout. One approach
     * is to find the time (UTC) from when the GenerateRequest input XML document is generated and add on
     * how much time you wish to give the customer before the payment page times out.
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTimeout($value)
    {
        return $this->setParameter('timeout', $value);
    }

    /**
     * Get the PxPay Timeout
     *
     * @return string
     */
    public function getTimeout()
    {
        return $this->getParameter('timeout');
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

        if ($this->getEmailAddress()) {
            $data->EmailAddress = $this->getEmailAddress();
        }

        if ($this->getTimeout()) {
            $data->Timeout = $this->getTimeout();
        }

        if ($this->getNotifyUrl()) {
            $data->UrlCallback = $this->getNotifyUrl();
        }

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
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [], $data->asXML());

        return $this->createResponse($httpResponse->getBody()->getContents());
    }

    /**
     * Create an authorize response
     *
     * @param  SimpleXMLElement $data
     * @return Omnipay\PaymentExpress\Message\PxPayAuthorizeResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new PxPayAuthorizeResponse($this, simplexml_load_string($data));
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
