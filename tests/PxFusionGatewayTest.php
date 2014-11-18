<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Tests\GatewayTestCase;

class PxFusionGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxFusionGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'currency' => 'NZD',
            'txnRef' => 'test',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PxFusionPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('000001001974701382c9911e025dc301', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://sec.paymentexpress.com/pxmi3/pxfusionauth', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame(array('SessionId' => '000001001974701382c9911e025dc301'), $response->getRedirectData());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PxFusionPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
