<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Interfaces\PaymentServiceInterface;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentService implements PaymentServiceInterface
{
    function all(): Collection
    {
        return Payment::all();
    }

    function pay(array $data, Payment $payment): array
    {
        if (($payment->type === PaymentType::CREDIT_CARD->getPaymentType()
            && $data['price'] == $payment->total_price) ||
            $payment->type === PaymentType::CASH->getPaymentType()) {

            $payment->update([
                'details' => 'completed'
            ]);
            
            $payment->booking()->update([
                'is_paid' => true
            ]);

            return ['payment successful', $payment];
        }

        return ['enter valid amount', $payment];
    }

    function update(array $data, Payment $payment): Payment
    {
        $payment->update($data);

        return $payment;
    }
}
