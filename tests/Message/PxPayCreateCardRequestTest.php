<?php
namespace Omnipay\PaymentExpress\Message;

use Omnipay\Tests\TestCase;

class PxPayCreateCardRequestTest extends TestCase
{
    /**
     * Test that we can set the action to purchase.
     */
    public function testCreateCardPurchaseSuccess()
    {
        $request = new PxPayCreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
              'returnUrl' => 'abc123',
              'action' => 'Purchase',
            )
        );
        $request->getData();
        $this->assertEquals('Purchase', $request->getAction());
    }
}
