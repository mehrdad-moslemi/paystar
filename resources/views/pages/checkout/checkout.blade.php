<x-app-layout>

    <div class="flex flex-col items-center justify-center">
        <h2>Do you want to buy <span class="underline">{{ $product->name }}</span> and create new order?</h2>

        <div class="mt-5 flex items-center space-x-3">
            <form action="{{ route('post.checkout') }}" method="post">
                @csrf
                <button type="submit" class="px-2 py-1 text-white bg-green-500 rounded-lg">Yes, Pay {{ $product->price }} rials</button>
            </form>
            <a href="/" class="underline">No, return to products</a>
        </div>
    </div>
</x-app-layout>