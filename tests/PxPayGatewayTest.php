<?php

namespace Omnipay\PaymentExpress;

use Omnipay\Tests\GatewayTestCase;

class PxPayGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PxPayGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('PxPayPurchaseSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

      $this->_testSuccessfulPurchase($response);
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('PxPayPurchaseFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getRedirectData());
        $this->assertNull($response->getRedirectUrl());
        $this->assertSame('Invalid Key', $response->getMessage());
    }

    public function testAuthorizeWithTransactionDataSuccess()
    {
        $this->setMockHttpResponse('PxPayPurchaseSuccess.txt');

        $options = array_merge($this->options, array(
            'description'      => 'TestReference',
            'transactionId'    => 'P075985DA31094D8',
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
        $this->assertSame($options['cardReference'], $request->getCardReference());

        $response = $request->send();

      $this->_testSuccessfulPurchase($response);
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PxPayPurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

      $this->_testSuccessfulPurchase($response);
    }

    public function testPurchaseWithCardReferenceSuccess()
    {
        $this->setMockHttpResponse('PxPayPurchaseSuccess.txt');

        $options = array_merge($this->options, array(
            'cardReference'     => 'Card reference',
            'EnableAddBillCard' => 1
        ));

        $response = $this->gateway->purchase($options)->send();

      $this->_testSuccessfulPurchase($response);
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PxPayPurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Invalid Key', $response->getMessage());
    }

    public function testCreateCardSuccess()
    {
        $this->setMockHttpResponse('PxPayCreateCardSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame('https://sec.paymentexpress.com/pxmi3/EF4054F622D6C4C1B0FA3975F5B37D5883A7AA411DF778AEBA9C4E3CBE1B394B50478552233E3FBD7', $response->getRedirectUrl());
        $this->assertSame('GET', $response->getRedirectMethod());
    }

    public function testCreateCardFailure()
    {
        $this->setMockHttpResponse('PxPayCreateCardFailure.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('userpass too short', $response->getMessage());
    }

    public function testCompleteAuthorizeSuccess()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseSuccess.txt');

        $response = $this->gateway->completeAuthorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompleteAuthorizeSuccessWithPostResult()
    {
        $this->getHttpRequest()->query->replace(array());
        $this->getHttpRequest()->request->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseSuccess.txt');

        $response = $this->gateway->completeAuthorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompleteAuthorizeFailure()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseFailure.txt');

        $response = $this->gateway->completeAuthorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Length of the data to decrypt is invalid.', $response->getMessage());
    }

    public function testCompleteCreateCardSuccess()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompleteCreateCardSuccess.txt');

        $response = $this->gateway->completeCreateCard($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('000000030a1806f0', $response->getTransactionReference());
        $this->assertSame('0000030007487668', $response->getCardReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompleteCreateCardFailure()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompleteCreateCardFailure.txt');

        $response = $this->gateway->completeCreateCard($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Length of the data to decrypt is invalid.', $response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompleteAuthorizeInvalid()
    {
        $this->getHttpRequest()->query->replace(array());

        $response = $this->gateway->completeAuthorize($this->options)->send();
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseSuccess.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getTransactionReference());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseWithTransactionDataSuccess()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseSuccess.txt');

        $options = array_merge($this->options, array(
            'MerchantReference' => 'TestReference',
            'TxnId'             => 'P075985DA31094D8',
            'TxnData1'          => 'Business Name',
            'TxnData2'          => 'Business Phone',
            'TxnData3'          => 'Business ID',
        ));

        $response = $this->gateway->completePurchase($options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('0000000103f5dc65', $response->getTransactionReference());
        $this->assertSame('TestReference', $response->getData()->MerchantReference->__toString());
        $this->assertSame('P075985DA31094D8', $response->getTransactionId());
        $this->assertSame('Business Name', $response->getData()->TxnData1->__toString());
        $this->assertSame('Business Phone', $response->getData()->TxnData2->__toString());
        $this->assertSame('Business ID', $response->getData()->TxnData3->__toString());
        $this->assertSame('APPROVED', $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $this->getHttpRequest()->query->replace(array('result' => 'abc123'));

        $this->setMockHttpResponse('PxPayCompletePurchaseFailure.txt');

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Length of the data to decrypt is invalid.', $response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->getHttpRequest()->query->replace(array());

        $response = $this->gateway->completePurchase($this->options)->send();
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

    private function _testSuccessfulPurchase($response)
    {
      $this->assertFalse($response->isSuccessful());
      $this->assertTrue($response->isRedirect());
      $this->assertNull($response->getTransactionReference());
      $this->assertNull($response->getMessage());
      $this->assertSame('https://sec.paymentexpress.com/pxpay/pxpay.aspx?userid=Developer&request=v5H7JrBTzH-4Whs__1iQnz4RGSb9qxRKNR4kIuDP8kIkQzIDiIob9GTIjw_9q_AdRiR47ViWGVx40uRMu52yz2mijT39YtGeO7cZWrL5rfnx0Mc4DltIHRnIUxy1EO1srkNpxaU8fT8_1xMMRmLa-8Fd9bT8Oq0BaWMxMquYa1hDNwvoGs1SJQOAJvyyKACvvwsbMCC2qJVyN0rlvwUoMtx6gGhvmk7ucEsPc_Cyr5kNl3qURnrLKxINnS0trdpU4kXPKOlmT6VacjzT1zuj_DnrsWAPFSFq-hGsow6GpKKciQ0V0aFbAqECN8rl_c-aZWFFy0gkfjnUM4qp6foS0KMopJlPzGAgMjV6qZ0WfleOT64c3E-FRLMP5V_-mILs8a',
        $response->getRedirectUrl());
      $this->assertSame('GET', $response->getRedirectMethod());
    }

}
