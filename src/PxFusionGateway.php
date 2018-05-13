<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Common\AbstractGateway;
use Omnipay\Omnipay;
use Omnipay\PaymentExpress\Message\PxFusionCreateCardRequest;

/**
 * DPS PaymentExpress PxFusion Gateway
 */
class PxFusionGateway extends AbstractGateway
{
    public function getName()
    {
        return 'PaymentExpress PxFusion';
    }

    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
        );
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

    public function purchase(array $parameters = array())
    {
        if (!empty($parameters['cardReference']) && $this->getPxPostPassword() && $this->getPxPostUsername()) {
            $gateway = Omnipay::create('PaymentExpress_PxPost');
            $gateway->setPassword($this->getPxPostPassword());
            $gateway->setUserName($this->getPxPostUsername());
            return $gateway->purchase($parameters);
        }
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxFusionPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxFusionCompletePurchaseRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxFusionCreateCardRequest', $parameters);
    }

    public function completeCreateCard(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }
}
