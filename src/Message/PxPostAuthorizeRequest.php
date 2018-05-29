<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress PxPost Authorize Request
 */
class PxPostAuthorizeRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://sec.paymentexpress.com/pxpost.aspx';
    protected $testEndpoint = 'https://uat.paymentexpress.com/pxpost.aspx';
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

    public function getEndpoint()
    {
        return $this->getTestMode() === true ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @return mixed
     */
    public function getReceiptEmail()
    {
        return $this->getParameter('ReceiptEmail');
    }

    /**
     * @param mixed $email
     * @return $this
     */
    public function setReceiptEmail($email)
    {
        $this->setParameter('ReceiptEmail', $email);

        return $this;
    }

    /**
     * Get the PxPost TxnData1
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
     * Set the PxPost TxnData1
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData1($value)
    {
        return $this->setParameter('transactionData1', $value);
    }

    /**
     * Get the PxPost TxnData2
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
     * Set the PxPost TxnData2
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData2($value)
    {
        return $this->setParameter('transactionData2', $value);
    }

    /**
     * Get the PxPost TxnData3
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
     * Set the PxPost TxnData3
     *
     * @param string $value Max 255 bytes
     * @return $this
     */
    public function setTransactionData3($value)
    {
        return $this->setParameter('transactionData3', $value);
    }

    /**
     * Get the PxPost BillingId where this is used instead of the DpsBillingId
     * for storing or retrieving a saved card profile.
     *
     * @see getCardReference() for DpsBillingId
     * @return mixed
     */
    public function getBillingId()
    {
        return $this->getParameter('billingId');
    }

    /**
     * Set a BillingId for use of a stored card with a local reference as an
     * alternative to using cardReference (which uses DPS' generated reference instead)
     *
     * @param string $value Max 32 bytes
     * @return $this
     */
    public function setBillingId($value)
    {
        return $this->setParameter('billingId', $value);
    }

    protected function getBaseData()
    {
        $data = new \SimpleXMLElement('<Txn />');
        $data->PostUsername = $this->getUsername();
        $data->PostPassword = $this->getPassword();
        $data->TxnType = $this->action;

        return $data;
    }

    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();
        $data->InputCurrency = $this->getCurrency();
        $data->Amount = $this->getAmount();

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
        } elseif ($this->getBillingId()) {
            $data->BillingId = $this->getBillingId();
        } elseif ($this->getCard()) {
            $this->getCard()->validate();
            $data->CardNumber = $this->getCard()->getNumber();
            $data->CardHolderName = $this->getCard()->getName();
            $data->DateExpiry = $this->getCard()->getExpiryDate('my');
            $data->Cvc2 = $this->getCard()->getCvv();
        } else {
            // either cardReference or card is required
            $this->validate('card');
        }

        if ($this->getReceiptEmail()) {
            $data->ReceiptEmail = $this->getReceiptEmail();
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), [], $data->asXML());

        $xml = simplexml_load_string($httpResponse->getBody()->getContents());

        return $this->response = new Response($this, $xml);
    }
}
