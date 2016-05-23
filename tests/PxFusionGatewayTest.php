<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Tests\GatewayTestCase;

class PxFusionGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxFusionGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'currency' => 'NZD',
            'txnRef' => 'test',
            'returnUrl' => 'https://www.example.com/return',
            'transactionId' => 123,
        );

        $this->completePurchaseOptions = array(
            'sessionId' => '000001001974701382c9911e025dc301',
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PxFusionPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://sec.paymentexpress.com/pxmi3/pxfusionauth', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame(array('SessionId' => '000001001974701382c9911e025dc301'), $response->getRedirectData());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PxFusionPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->setMockHttpResponse('PxFusionCompletePurchaseSuccess.txt');

        $response = $this->gateway->completePurchase($this->completePurchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000010a838c1c', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $this->setMockHttpResponse('PxFusionCompletePurchaseFailure.txt');

        $response = $this->gateway->completePurchase($this->completePurchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000011a0ae597', $response->getTransactionReference());
        $this->assertSame('DECLINED', $response->getMessage());
        $this->assertSame(1, $response->getCode());
    }
}
