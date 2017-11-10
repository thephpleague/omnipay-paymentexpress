<?php

namespace Omnipay\PaymentExpress\Message;

use DomDocument;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * PaymentExpress PxFusion Purchase Response
 */
class PxFusionPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public $namespace = 'http://schemas.datacontract.org/2004/07/';

    /**
     * PxFusionPurchaseResponse constructor.
     *
     * @param RequestInterface $request
     * @param \Guzzle\Http\EntityBody $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $responseDom = new DOMDocument;
        $responseDom->loadXML($data);

        $result = simplexml_import_dom($responseDom->documentElement->firstChild->firstChild->firstChild);

        $this->data = $result->children($this->namespace);
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return ((string) $this->data->success === 'true');
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function getSessionId()
    {
        if ($this->isRedirect()) {
            return (string) $this->data->sessionId;
        }
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return 'https://sec.paymentexpress.com/pxmi3/pxfusionauth';
        }
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'SessionId' => $this->getSessionId(),
            );
        }
    }
}
