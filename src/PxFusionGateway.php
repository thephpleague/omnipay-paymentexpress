<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Common\AbstractGateway;

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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentExpress\Message\PxFusionPurchaseRequest', $parameters);
    }
}
