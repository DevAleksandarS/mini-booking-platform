<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($users->count())
                        <ul class="list-disc pl-5 flex flex-col gap-2">
                            @foreach($users as $user)
                                <li>
                                    {{ $user->name }} ({{ $user->email }})

                                    <div class="flex gap-2">
                                        <form action="{{ route('user.update_role', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="role"
                                                value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                                            <button type="submit" class="bg-blue-700 text-white px-2 rounded">
                                                {{ $user->role === 'admin' ? 'Change role to User' : 'Change role to Admin' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('user.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-2 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if($users->hasPages())
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @else
                        <p>No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>