<?php

namespace Lloricode\Paymaya\Tests;

use ErrorException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Lloricode\Paymaya\Client\Checkout\CheckoutClient;
use Lloricode\Paymaya\Request\Checkout\Checkout;
use Lloricode\Paymaya\Response\Checkout\PaymentDetail\PaymentDetail;

class CheckoutTest extends TestCase
{
    /** @test */
    public function json_check_exact_from_docs()
    {
        $this->assertSame(
            json_encode(json_decode(self::jsonCheckoutDataFromDocs(), true), JSON_PRETTY_PRINT),
            json_encode(self::buildCheckout(), JSON_PRETTY_PRINT)
        );
    }

    /**
     * @test
     */
    public function check_via_sandbox()
    {
        $id = 'test-generated-id';
        $url = 'http://test';

        $mock = new MockHandler(
            [
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'checkoutId' => $id,
                            'redirectUrl' => $url,
                        ]
                    ),
                ),
            ]
        );

        try {
            $checkoutResponse = (new CheckoutClient(self::generatePaymayaClient($mock)))
                ->execute(self::buildCheckout());
        } catch (ErrorException $e) {
            $this->fail('ErrorException');
        } catch (ClientException $e) {
            $this->fail('ClientException: '.$e->getMessage().$e->getResponse()->getBody());
        } catch (GuzzleException $e) {
            $this->fail('GuzzleException');
        }

        $this->assertEquals($id, $checkoutResponse->checkoutId);
        $this->assertEquals($url, $checkoutResponse->redirectUrl);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @test
     */
    public function show_with_id_success()
    {
//        $this->markTestSkipped();
        $responseData = '{
    "id": "4ef96167-b8f2-4400-912e-5bd2f4289cfb",
    "items": [
        {
            "name": "Wings (8pcs)",
            "quantity": "2",
            "code": "wings-8pcs",
            "description": "no description",
            "amount": {
                "value": 365,
                "details": {
                    "discount": "0",
                    "serviceCharge": "0",
                    "shippingFee": "0",
                    "tax": "0",
                    "subtotal": "730"
                }
            },
            "totalAmount": {
                "value": 730,
                "details": {
                    "discount": "0",
                    "serviceCharge": "0",
                    "shippingFee": "0",
                    "tax": "0",
                    "subtotal": "730"
                }
            }
        },
        {
            "name": "Garlic Gold",
            "quantity": "4",
            "description": "Wings (8pcs) (variants)",
            "amount": {
                "value": 0,
                "details": {
                    "discount": "0",
                    "serviceCharge": "0",
                    "shippingFee": "0",
                    "tax": "0",
                    "subtotal": "0"
                }
            },
            "totalAmount": {
                "value": 0,
                "details": {
                    "discount": "0",
                    "serviceCharge": "0",
                    "shippingFee": "0",
                    "tax": "0",
                    "subtotal": "0"
                }
            }
        }
    ],
    "metadata": null,
    "requestReferenceNumber": "TEST4BRANC_W2102220001",
    "receiptNumber": "3b2b7ec1a3c0",
    "createdAt": "2021-02-22T02:28:13.000Z",
    "updatedAt": "2021-02-22T02:28:54.000Z",
    "paymentScheme": "visa",
    "expressCheckout": true,
    "refundedAmount": "0",
    "canPayPal": false,
    "expiredAt": "2021-02-22T03:28:13.000Z",
    "status": "COMPLETED",
    "paymentStatus": "PAYMENT_SUCCESS",
    "paymentDetails": {
        "responses": {
            "efs": {
                "paymentTransactionReferenceNo": "854ae496-1725-403b-a2c4-3b2b7ec1a3c0",
                "status": "SUCCESS",
                "receipt": {
                    "transactionId": "c33c40b6-1d69-4d7e-95f9-55b2fcab7b34",
                    "receiptNo": "3b2b7ec1a3c0",
                    "approval_code": "00001234",
                    "approvalCode": "00001234"
                },
                "payer": {
                    "fundingInstrument": {
                        "card": {
                            "cardNumber": "412345******1522",
                            "expiryMonth": "12",
                            "expiryYear": "2025"
                        }
                    }
                },
                "amount": {
                    "total": {
                        "currency": "PHP",
                        "value": 730
                    }
                },
                "created_at": "2021-02-22T02:29:09.361Z"
            }
        },
        "paymentAt": "2021-02-22T02:28:56.000Z",
        "3ds": true
    },
    "buyer": {
        "contact": {
            "phone": "+639123456789",
            "email": "system@wing-zone.com"
        },
        "firstName": "Administrator",
        "lastName": "System",
        "billingAddress": [],
        "shippingAddress": {
            "line1": "1234",
            "countryCode": "PH"
        }
    },
    "merchant": {
        "currency": "PHP",
        "email": "paymentgatewayteam@paymaya.com",
        "locale": "en",
        "homepageUrl": "http:\/\/www.paymaya.com",
        "isEmailToMerchantEnabled": false,
        "isEmailToBuyerEnabled": true,
        "isPaymentFacilitator": false,
        "isPageCustomized": true,
        "supportedSchemes": [
            "Mastercard",
            "Visa",
            "JCB"
        ],
        "canPayPal": false,
        "payPalEmail": null,
        "payPalWebExperienceId": null,
        "expressCheckout": true,
        "name": "PayMaya Developers Portal"
    },
    "totalAmount": {
        "amount": "730",
        "currency": "PHP",
        "details": {
            "discount": "0",
            "serviceCharge": "0",
            "shippingFee": "0",
            "tax": "0",
            "subtotal": "730"
        }
    },
    "redirectUrl": {
        "success": "https:\/\/staging-api1",
        "failure": "https:\/\/staging-api2",
        "cancel": "https:\/\/staging-api3"
    },
    "transactionReferenceNumber": "xxx"
}';
        $mock = new MockHandler(
            [
                new Response(
                    200,
                    [],
                    $responseData,
                ),
            ]
        );

        $checkoutResponse = (new CheckoutClient(self::generatePaymayaClient($mock)))
            ->retrieve('4ef96167-b8f2-4400-912e-5bd2f4289cfb');

        $this->assertInstanceOf(Checkout::class, $checkoutResponse);

        $this->assertEquals('4ef96167-b8f2-4400-912e-5bd2f4289cfb', $checkoutResponse->id);
        $this->assertInstanceOf(PaymentDetail::class, $checkoutResponse->paymentDetails);

//        $sortAndEncode = function(string $json): string {
//            $array = (array) json_decode($json, true);
//            array_multisort($array);
//            return json_encode($array,JSON_PRETTY_PRINT);
//        };
//
//        $this->assertSame($sortAndEncode($responseData), $sortAndEncode(json_encode($checkoutResponse->toArray())));

//        $this->assertContains((array) json_decode($responseData, true),$checkoutResponse->toArray());
    }
}
