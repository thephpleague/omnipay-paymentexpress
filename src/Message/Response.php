<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * PaymentExpress Response
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return 1 === (int) $this->data->Success;
    }

    public function getTransactionReference()
    {
        return empty($this->data->DpsTxnRef) ? null : (string) $this->data->DpsTxnRef;
    }

    public function getTransactionId()
    {
        return empty($this->data->TxnId) ? null : (string) $this->data->TxnId;
    }

    public function getCardReference()
    {
        if (! empty($this->data->Transaction->BillingId)) {
            return (string) $this->data->Transaction->BillingId;
        } elseif (! empty($this->data->BillingId)) {
            return (string) $this->data->BillingId;
        }

        return null;
    }

    public function getMessage()
    {
        if (isset($this->data->HelpText)) {
            return (string) $this->data->HelpText;
        } else {
            return (string) $this->data->ResponseText;
        }
    }
}
