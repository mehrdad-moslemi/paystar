<x-app-layout>

    <div class="flex flex-col items-center justify-center space-y-3">
        <h3>Your order has been created</h3>

        @if (auth()->user()->is_card_verified)
            <h3 class="text-green-600">Your card number is verified</h3>
        @else
            <h3 class="text-red-500">Your card number is not verified</h3>
        @endif
        
        <p class="max-w-xl">Your card number is : '{{ auth()->user()->card_number }}'</p>
        <p>You can make transaction only with above card number, if it has been verified.</p>
        <p>If you use a card number other than the above, your payment and order will not be approved after transaction.</p>
        
        <form action="https://core.paystar.ir/api/pardakht/payment" method="post">
            <input type="hidden" name="token" value="{{ $token }}">
            <button type="submit" class="px-2 py-1 text-white bg-green-500 rounded-lg">Transfer to the payment gateway</button>
        </form>
    </div>

</x-app-layout>