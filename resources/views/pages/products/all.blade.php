<x-app-layout>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
        
            <div class="p-4 bg-white rounded-lg">
                <h2>{{ $product->name }}</h2>

                <div class="mt-10 flex justify-between items-center">
                    <div>{{ $product->price }}</div>

                    <form action="{{ route('buy.product') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="px-2 py-1 text-sm text-white bg-indigo-500 rounded-lg">Buy Product</button>
                    </form>
                </div>
            </div>

        @endforeach
    </div>

    <div class="mt-10">
        {{ $products->render() }}
    </div>

</x-app-layout>