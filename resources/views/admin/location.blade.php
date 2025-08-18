<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Locations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('location.store') }}" method="POST" class="flex flex-col gap-3">
                        @csrf

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="city" name="city" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" id="country" name="country" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                            Add Location
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden mt-3 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($locations->count())
                        <ul class="list-disc pl-5 flex flex-col gap-2">
                            @foreach($locations as $location)
                                <li>{{ $location->city }}, {{ $location->country }}
                                    <form action="{{ route('location.destroy', $location) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-2 rounded"
                                            onclick="return confirm('Are you sure you want to delete this location?')">
                                            Delete
                                        </button>
                                    </form>

                                    <button type="button" class="bg-blue-700 text-white px-2 rounded" data-bs-toggle="modal"
                                        data-bs-target="#locationPopup{{ $location->id }}">
                                        Update
                                    </button>
                                </li>

                                <div class="modal fade" id="locationPopup{{ $location->id }}" tabindex="-1"
                                    aria-labelledby="locationPopup{{ $location->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="locationPopup{{ $location->id }}Label">Update
                                                    Location</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('location.update', $location) }}" method="POST"
                                                    id="updateLocationForm{{ $location->id }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label for="city{{ $location->id }}" class="form-label">City</label>
                                                        <input type="text"
                                                            class="form-control @error('city') is-invalid @enderror"
                                                            id="city{{ $location->id }}" name="city"
                                                            value="{{ old('city', $location->city) }}" required>
                                                        @error('city')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="country{{ $location->id }}"
                                                            class="form-label">Country</label>
                                                        <input type="text"
                                                            class="form-control @error('country') is-invalid @enderror"
                                                            id="country{{ $location->id }}" name="country"
                                                            value="{{ old('country', $location->country) }}" required>
                                                        @error('country')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary"
                                                    form="updateLocationForm{{ $location->id }}">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>

                        @if($locations->hasPages())
                            <div class="mt-4">
                                {{ $locations->links() }}
                            </div>
                        @endif
                    @else
                        <p>No locations found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>