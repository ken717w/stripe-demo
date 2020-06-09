<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Payment;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private $_stripeClient;

    public function searchHistory()
    {
        return view('history.search');
    }

    public function showHistory(Request $request)
    {
        $validatedData = $request->validate([
            'customerEmail' => ['bail', 'required', 'email'],
            'paymentRef' => ['bail', 'required', 'string'],
        ]);

        $customer = Customer::where('email', $validatedData['customerEmail'])->first();
        $payment = Payment::where('stripe_ref', $validatedData['paymentRef'])->first();

        if (is_null($customer) || is_null($payment) || $payment->customer_id !== $customer->id) {
            $validatedData = $request->validate([
                'customerEmail' => [function ($attribute, $value, $fail) {
                    $fail('Record not exist.');
                }],
            ]);
        }

        $stripe = $this->getStripeClient();
        $stripePayment = $stripe->paymentIntents->retrieve($payment->stripe_ref, []);

        return view('history.show', [
            'customerName' => $customer->name,
            'customerPhone' => $customer->phone,
            'customerEmail' => $customer->email,
            'orderCurrency' => $stripePayment->amount / 100,
            'orderPrice' => strtoupper($stripePayment->currency),
        ]);
    }

    private function getStripeClient()
    {
        if ($this->_stripeClient === null) {
            $this->_stripeClient = new \Stripe\StripeClient(config('stripe.secret_key'));
        }

        return $this->_stripeClient;
    }
}
