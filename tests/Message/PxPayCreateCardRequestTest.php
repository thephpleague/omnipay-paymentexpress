<?php
/**
 * @file
 * Created by PhpStorm.
 * User: eileen
 * Date: 7/04/2016
 * Time: 12:25 AM
 */

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Tests\TestCase;

/**
 * Class PxPayCreateCardRequestTest
 * @package Omnipay\PaymentExpress\Message
 */
class PxPayCreateCardRequestTest extends TestCase{

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
              'card' => [
                  'email' => "test@example.net",
                  'name' => "JDFKL FDJKSL",
                  'phone' => "123 456 7890",
                  'shippingAddress1' => "Ship 1 Test",
                  'shippingAddress2' => "Ship 2 Test",
                  'shippingCity'     => "Ship 4 City",
                  'shippingPostcode' => "Ship 5 Postcode",
                  'shippingState'    => "Ship 6 State",
                  'shippingCountry'  => "Ship 7 Country",
                  'billingAddress1'  => "Bill 1 Test",
                  'billingAddress2'  => "Bill 2 Test",
                  'billingCity'      => "Bill 4 City",
                  'billingPostcode'  => "Bill 5 Postcode",
                  'billingState'     => "Bill 6 State",
                  'billingCountry'   => "Bill 7 Country",
              ],
          )
      );
      $request->getData();
      $this->assertEquals('Purchase', $request->getAction());
  }
}
