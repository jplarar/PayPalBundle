<?php

namespace Jplarar\PayPalBundle\Services;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;

/**
 * Class PayPalClient
 * @package Jplarar\PayPalBundle\Services
 */
class PayPalClient
{
    protected $apiContext;
    protected $paypal_redirect_success;
    protected $paypal_redirect_error;

    /**
     * PayPalClient constructor.
     * @param string $paypal_client_id
     * @param string $paypal_client_secret
     * @param string $paypal_redirect_success
     * @param string $paypal_redirect_error
     */
    public function __construct($paypal_client_id = "", $paypal_client_secret = "", $paypal_redirect_success = "", $paypal_redirect_error = "")
    {
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $paypal_client_id,
                $paypal_client_secret
            )
        );

        $this->paypal_redirect_success = $paypal_redirect_success;
        $this->paypal_redirect_error = $paypal_redirect_error;
    }

    /**
     * @param $paymentAmount
     * @param $description
     * @param string $currency
     * @return string|null
     */
    public function createPayment($paymentAmount, $description, $currency = 'MXN')
    {
        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Set redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->paypal_redirect_success)->setCancelUrl($this->paypal_redirect_error);

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency($currency)->setTotal($paymentAmount);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setDescription($description);

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContext);
            // Get PayPal redirect URL and redirect the customer
            $approvalUrl = $payment->getApprovalLink();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $approvalUrl;
    }

    /**
     * @param $paymentId
     * @param $payerId
     * @return Payment
     */
    public function executePayment($paymentId, $payerId)
    {
        // Get payment object by passing paymentId
        $payment = Payment::get($paymentId, $this->apiContext);
        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {
            // Execute payment
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $result;
    }

}