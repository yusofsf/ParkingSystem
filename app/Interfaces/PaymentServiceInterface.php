<?php

namespace App\Interfaces;

use App\Models\Payment;

interface PaymentServiceInterface
{
    function all();

    function update(array $data, Payment $payment);

    function pay(array $data, Payment $payment);
}
