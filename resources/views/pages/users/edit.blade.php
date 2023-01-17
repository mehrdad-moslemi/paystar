<x-app-layout>

    <form action="{{ route('users.update' , $user->id) }}" method="post" class="max-w-sm mx-auto flex flex-col items-center justify-center space-y-3">
        @method('PUT')
        @csrf

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="w-full flex flex-col">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value={{ old('name' , $user->name) }}>
        </div>

        <div class="w-full flex flex-col">
            <label for="card_number">Card Number</label>
            <input type="text" name="card_number" id="card_number" value={{ old('card_number' , $user->card_number) }}>
        </div>

        <div class="w-full flex flex-col">
            <label for="is_card_verified">Card Active Status</label>
            <select name="is_card_verified" id="is_card_verified">
                <option value="0" {{ $user->is_card_verified ? '' : 'selected' }}>Not Active</option>
                <option value="1" {{ $user->is_card_verified ? 'selected' : '' }}>Active</option>
            </select>
        </div>

        <button type="submit" class="px-4 py-2 text-white bg-sky-400 rounded-lg">Update</button>

    </form>
    
</x-app-layout>