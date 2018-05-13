<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PxPostPurchaseSuccess.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PxPostPurchaseFailure.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testCompletePurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PxPayCompletePurchaseSuccess.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PxPayCompletePurchaseFailure.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Length of the data to decrypt is invalid.', $response->getMessage());
    }

    public function testCreateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PxPostCreateCardSuccess.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('00000001040c73ea', $response->getTransactionReference());
        $this->assertSame('0000010009328404', $response->getCardReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testCreateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PxPostCreateCardFailure.txt');
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        $response = new Response($this->getMockRequest(), $xml);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('An Invalid Card Number was entered. Check the card number', $response->getMessage());
    }
}
