<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Payment;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $_stripeClient;

    public function showCart()
    {
        return view('cart.show');
    }

    public function processCart(Request $request)
    {
        $validatedData = $request->validate([
            'customerName' => ['bail', 'required', 'string', 'max:255'],
            'customerPhone' => ['bail', 'required', 'digits:8'],
            'customerEmail' => ['bail', 'required', 'email'],
            'orderCurrency' => ['bail', 'required', function ($attribute, $value, $fail) {
                if ($value !== 'HKD') {
                    $fail('Support of currencies other than HKD is coming soon.');
                }
            }],
            'orderPrice' => ['bail', 'required', 'numeric', 'gte:4', 'lte:999999.99'],
            'cardHolder' => ['bail', 'required', 'string', 'max:255'],
            'cardNumber' => ['bail', 'required', 'digits_between:14,16'],
            'cardExpiryMonth' => ['bail', 'required', 'digits:2', 'gte:1', 'lte:12'],
            'cardExpiryYear' => ['bail', 'required', 'digits:2'],
            'cardCvc' => ['bail', 'required', 'digits_between:3,4'],
        ]);
        $validatedData['orderPrice'] = round($validatedData['orderPrice'] * 100);

        $stripe = $this->getStripeClient();

        // Create payment method
        try {
            $paymentMethod = $stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $validatedData['cardNumber'],
                    'exp_month' => $validatedData['cardExpiryMonth'],
                    'exp_year' => $validatedData['cardExpiryYear'],
                    'cvc' => $validatedData['cardCvc'],
                ],
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $request->validate([
                'cardNumber' => [function ($attribute, $value, $fail) use ($e) {
                    $fail($e->getMessage());
                }],
            ]);
        }

        if ($paymentMethod->card->brand === 'amex') {
            $request->validate([
                'cardNumber' => [function ($attribute, $value, $fail) {
                    $fail('American Express is not supported.');
                }],
            ]);
        }

        // Create payment intent
        try {
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $validatedData['orderPrice'],
                'currency' => $validatedData['orderCurrency'],
                'confirm' => true,
                'metadata' => ['integration_check' => 'accept_a_payment'],
                'payment_method' => $paymentMethod->id,
                'receipt_email' => $validatedData['customerEmail'],
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            $request->validate([
                'cardNumber' => [function ($attribute, $value, $fail) use ($e) {
                    $fail($e->getMEssage());
                }],
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $request->validate([
                'cardNumber' => [function ($attribute, $value, $fail) use ($e) {
                    $fail($e->getMessage());
                }],
            ]);
        }

        if ($paymentIntent->status === 'succeeded') {
            $this->postProcessCart($validatedData, $paymentIntent);

            return view('cart.process', [
                'paymentRef' => $paymentIntent->id,
            ]);
        } else {
            $request->validate([
                'cardNumber' => [function ($attribute, $value, $fail) use ($paymentIntent) {
                    $fail('Failed to confirm payment, payment status: ' . $paymentIntent->status . '.');
                }],
            ]);
        }
    }

    private function postProcessCart(array $formData, \Stripe\PaymentIntent $paymentIntent)
    {
        $stripe = $this->getStripeClient();

        // Store or update customer information
        $customer = Customer::where('email', $formData['customerEmail'])->first();
        if ($customer === null) {
            $stripeCustomer = $stripe->customers->create([
                'name' => $formData['customerName'],
                'email' => $formData['customerEmail'],
                'phone' => $formData['customerPhone'],
            ]);

            $customer = new Customer;
            $customer->stripe_ref = $stripeCustomer->id;
            $customer->name = $formData['customerName'];
            $customer->email = $formData['customerEmail'];
            $customer->phone = $formData['customerPhone'];
            $customer->save();
        } else {
            if ($customer->name !== $formData['customerName'] || $customer->phone !== $formData['customerPhone']) {
                $stripeCustomer = $stripe->customers->update($customer->stripe_ref, [
                    'name' => $formData['customerName'],
                    'phone' => $formData['customerPhone'],
                ]);

                $customer->name = $formData['customerName'];
                $customer->phone = $formData['customerPhone'];
                $customer->save();
            }
        }

        // Attach customer to payment
        $stripe->paymentIntents->update($paymentIntent->id, [
            'customer' => $customer->stripe_ref,
        ]);

        // Store payment information
        $payment = new Payment;
        $payment->stripe_ref = $paymentIntent->id;
        $customer->payments()->save($payment);
    }

    private function getStripeClient()
    {
        if ($this->_stripeClient === null) {
            $this->_stripeClient = new \Stripe\StripeClient(config('stripe.secret_key'));
        }

        return $this->_stripeClient;
    }
}
