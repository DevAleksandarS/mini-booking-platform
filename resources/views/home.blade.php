<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($facilities->count())
                        <div class="grid grid-cols-4 gap-5">
                            @foreach($facilities as $facility)
                                <a href="{{ route('facility', ['id' => $facility->id]) }}">
                                    <img src="{{ Storage::url($facility->image) }}" alt="{{ $facility->name }}" class="w-36">
                                    <p>Name: {{ $facility->name }}</p>
                                    <p>Location: {{ $facility->location->city }}, {{ $facility->location->country }}</p>
                                    <p>Price: ${{ $facility->price }}</p>
                                    <p>Number of beds: {{ $facility->number_of_beds }}</p>
                                    <p>Max people: {{ $facility->max_people }}</p>
                                    <p>Description: {{ $facility->description }}</p>
                                </a>
                            @endforeach
                        </div>

                        @if($facilities->hasPages())
                            <div class="mt-4">
                                {{ $facilities->links() }}
                            </div>
                        @endif
                    @else
                        <p>No facilities found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>