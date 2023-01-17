<x-app-layout>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($users as $user)
        
            <div class="p-4 bg-white rounded-lg space-y-2">
                <h3>Name: {{ $user->name }}</h3>
                <h4>Card Number: {{ $user->card_number }}</h4>
                <h5>Card Number Status: 
                    @if ($user->is_card_verified)
                        <span class="text-green-500">Active</span>
                    @else
                        <span class="text-red-500">Not Active</span>
                    @endif
                </h5>

                <a class="inline-block px-2 py-1 text-sm text-white bg-sky-500 rounded-lg" href="{{ route('users.edit' , $user->id) }}">Edit User</a>
            </div>

        @endforeach
    </div>
    
</x-app-layout>