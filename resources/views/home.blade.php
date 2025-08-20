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
                    <div class="mb-6">
                        <form method="GET" action="{{ route('home') }}" class="mb-4 space-y-2">
                            <div>
                                <p class="font-semibold">Sort by:</p>
                                <label class="mr-2">
                                    <input type="radio" name="sort" value="name_asc" {{ request('sort') === 'name_asc' ? 'checked' : '' }}>
                                    Name Asc
                                </label>
                                <label class="mr-2">
                                    <input type="radio" name="sort" value="name_desc" {{ request('sort') === 'name_desc' ? 'checked' : '' }}>
                                    Name Desc
                                </label>
                                <label class="mr-2">
                                    <input type="radio" name="sort" value="price_asc" {{ request('sort') === 'price_asc' ? 'checked' : '' }}>
                                    Price Lower
                                </label>
                                <label>
                                    <input type="radio" name="sort" value="price_desc" {{ request('sort') === 'price_desc' ? 'checked' : '' }}>
                                    Price Higher
                                </label>
                            </div>

                            <div>
                                <p class="font-semibold">Price Range ($):</p>
                                <input type="number" name="price_min" placeholder="Min" class="border px-2 py-1 mr-2"
                                    value="{{ request('price_min') }}">
                                <input type="number" name="price_max" placeholder="Max" class="border px-2 py-1"
                                    value="{{ request('price_max') }}">
                            </div>

                            <div>
                                <p class="font-semibold">Max People:</p>
                                <input type="number" name="max_people" min="1" class="border px-2 py-1"
                                    value="{{ request('max_people') }}">
                            </div>

                            <div>
                                <p class="font-semibold">Location:</p>
                                <select name="location_id" class="border px-2 py-1">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->city }}, {{ $location->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="bg-blue-700 text-white px-2 rounded">
                                Search
                            </button>
                        </form>

                    </div>

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