<x-app-layout>

    <div class="mt-4 max-w-md mx-auto flex flex-col items-center justify-center space-y-2">
        <h2 class="text-green-600">Thanks for your purchase !</h2>
        <p>Transaction was successfull and here is your <span class="underline">order details</span>:</p>
        
        <div class="p-2 w-full flex flex-col items-center justify-center bg-white rounded-lg space-y-2">
            <div>Order Unique ID : {{ $payment->order_id }}</div>
            <div>Tracking Code : {{ $payment->tracking_code }}</div>
            <div>Order Amount : {{ $payment->order ? $payment->order->total_amount : '' }}</div>
            <div>Paid Amount : {{ $payment->payment_amount }}</div>
        </div>

        <h3>Products:</h3>

        @if ($payment->order && $payment->order->products)
            <ul class="p-2 w-full flex flex-col items-center justify-center bg-white rounded-lg">
                @foreach ($payment->order->products as $product)
                    <li class="flex justify-between items-center space-x-2">
                        <span>{{ $product->product->name }}</span>
                        <span>{{ $product->price }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('all-products') }}" class="mt-2 underline">Retrun to products</a>
    </div>

</x-app-layout>