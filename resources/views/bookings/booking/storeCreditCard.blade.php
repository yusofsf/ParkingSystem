@extends('layouts.app')

@section('title', 'store credit card')

@section('content')
    <div class="mx-auto max-w-md rounded-lg bg-white p-6 shadow-md">
        <h2 class="mb-6 text-center text-2xl font-bold">Enter Your Credit Card</h2>

        <form id="creditCardForm" class="space-y-4">
            <div>
                <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                <input
                    type="text"
                    id="card_number"
                    name="card_number"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="1234 5678 9012 3456"
                    maxlength="19"
                    minlength="12"
                    required
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="exp_month" class="block text-sm font-medium text-gray-700">Expiration Month</label>
                    <input
                        type="text"
                        id="exp_month"
                        name="exp_month"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    />
                </div>

                <div>
                    <label for="exp_year" class="block text-sm font-medium text-gray-700">Expiration Year</label>
                    <input
                        type="text"
                        id="exp_year"
                        name="exp_year"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    />
                </div>
            </div>

            <div>
                <label for="cvv2" class="block text-sm font-medium text-gray-700">CVV</label>
                <input
                    type="text"
                    id="cvv2"
                    name="cvv2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="123"
                    maxlength="4"
                    required
                />
            </div>

            {{-- <div class="mt-6"> --}}
            {{-- <button type="submit" --}}
            {{-- class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> --}}
            {{-- cancel --}}
            {{-- </button> --}}
            {{-- </div> --}}
            <div class="mt-6" id="">
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Save Card
                </button>
            </div>
        </form>
    </div>

    <script>
        const bookingId = window.location.pathname.split('/')[2];

        document.getElementById('creditCardForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                card_number: formatCardNumber(document.getElementById('card_number').value),
                exp_month: document.getElementById('exp_month').value,
                exp_year: document.getElementById('exp_year').value,
                cvv2: onlyNumbers(document.getElementById('cvv2').value),
            };

            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include',
                });
                const response = await fetch(`/api/bookings/${bookingId}/payments/creditCard`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    credentials: 'include',
                    body: JSON.stringify(formData),
                });

                const data = JSON.parse(await response.text());

                if (response.ok) {
                    alert(data.message);
                    window.location.href = `/payments/${data.result.id}/pay`;
                } else {
                    if (response.status === 403) {
                        alert(data.message);
                        window.location.href = '/dashboard';
                        return;
                    }
                    if (data.errors) {
                        let errorMessage = '';
                        Object.keys(data.errors).forEach((key) => {
                            errorMessage += data.errors[key][0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert(data.message || 'Failed to update car. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });

        function formatCardNumber(cardNumber) {
            // Format card number with spaces
            let value = cardNumber.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            return formattedValue;
        }

        function onlyNumbers(cvv) {
            return cvv.replace(/[^0-9]/g, '');
        }
    </script>
@endsection
