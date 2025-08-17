<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Locations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"></div>
            </div>

            <div class="bg-white overflow-hidden mt-3 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($locations->count())
                        <ul class="list-disc pl-5">
                            @foreach($locations as $location)
                                <li>{{ $location->city }}, {{ $location->country }}</li>
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