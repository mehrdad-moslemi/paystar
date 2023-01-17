<x-app-layout>

    <div class="mt-4 flex flex-col items-center justify-center">
        <p class="text-red-500">Order Failed</p>

        <div class="mt-2">Order Unique ID: {{ $orderId }}</div>

        <a href="{{ route('all-products') }}" class="mt-2 underline">Retrun to products and try again</a>
    </div>

</x-app-layout>