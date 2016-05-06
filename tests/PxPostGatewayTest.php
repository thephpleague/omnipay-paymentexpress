<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Tests\GatewayTestCase;

class PxPostGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxPostGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('PxPostPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testAuthorizeWithTransactionDataSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array_merge($this->options, array(
            'description'      => 'TestReference',
            'transactionId'    => 'inv1278',
            'transactionData1' => 'Business Name',
            'transactionData2' => 'Business Phone',
            'transactionData3' => 'Business ID',
            'cardReference'    => '000000030884cdc6'
        ));

        $request = $this->gateway->authorize($options);

        $this->assertSame($options['description'], $request->getDescription());
        $this->assertSame($options['transactionId'], $request->getTransactionId());
        $this->assertSame($options['transactionData1'], $request->getTransactionData1());
        $this->assertSame($options['transactionData2'], $request->getTransactionData2());
        $this->assertSame($options['transactionData3'], $request->getTransactionData3());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('TestReference', $response->getData()->MerchantReference->__toString());
        $this->assertSame('inv1278', $response->getData()->TxnRef->__toString());
        $this->assertSame('Business Name', $response->getData()->TxnData1->__toString());
        $this->assertSame('Business Phone', $response->getData()->TxnData2->__toString());
        $this->assertSame('Business ID', $response->getData()->TxnData3->__toString());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => '10.00',
            'transactionReference' => '000000030884cdc6',
        );

        $response = $this->gateway->capture($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getTransactionReference());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseWithTransactionDataSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array_merge($this->options, array(
            'description'      => 'TestReference',
            'transactionId'    => 'inv1278',
            'transactionData1' => 'Business Name',
            'transactionData2' => 'Business Phone',
            'transactionData3' => 'Business ID',
        ));

        $response = $this->gateway->purchase($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030884cdc6', $response->getTransactionReference());
        $this->assertSame('TestReference', $response->getData()->MerchantReference->__toString());
        $this->assertSame('inv1278', $response->getData()->TxnRef->__toString());
        $this->assertSame('Business Name', $response->getData()->TxnData1->__toString());
        $this->assertSame('Business Phone', $response->getData()->TxnData2->__toString());
        $this->assertSame('Business ID', $response->getData()->TxnData3->__toString());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PxPostPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('The transaction was Declined (U5)', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('PxPostPurchaseSuccess.txt');

        $options = array(
            'amount' => '10.00',
            'transactionReference' => '000000030884cdc6',
        );

        $response = $this->gateway->refund($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('000000030884cdc6', $response->getTransactionReference());
    }

    public function testCreateCardSuccess()
    {
        $this->setMockHttpResponse('PxPostCreateCardSuccess.txt');
        $response = $this->gateway->createCard($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('00000001040c73ea', $response->getTransactionReference());
        $this->assertSame('0000010009328404', $response->getCardReference());
        $this->assertSame('Transaction Approved', $response->getMessage());
    }

    public function testCreateCardFailure()
    {
        $this->setMockHttpResponse('PxPostCreateCardFailure.txt');
        $response = $this->gateway->createCard($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('An Invalid Card Number was entered. Check the card number', $response->getMessage());
    }

    public function testTestModeDisabled()
    {
        $options = array(
            'testMode' => false
        );

        $request = $this->gateway->authorize($options);

        $this->assertFalse($request->getTestMode());
        $this->assertContains('sec.paymentexpress.com', $request->getEndpoint());
    }

    public function testTestModeEnabled()
    {
        $options = array(
            'testMode' => true
        );

        $request = $this->gateway->authorize($options);

        $this->assertTrue($request->getTestMode());
        $this->assertContains('uat.paymentexpress.com', $request->getEndpoint());
    }
}
