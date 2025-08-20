<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Active Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($reservations->count())
                        <ul class="list-disc pl-5 flex flex-col gap-2">
                            @foreach($reservations as $reservation)
                                <li class="flex justify-between items-center py-2">
                                    <div>
                                        <p>
                                            <strong>
                                                {{ $reservation->user->name }}
                                            </strong>
                                        </p>
                                        <strong>
                                            {{ $reservation->facility->name }}
                                            @if($reservation->confirmed_at === null)
                                                (Needs Confirmation)
                                            @endif
                                        </strong><br>
                                        <span>
                                            {{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y') }}
                                        </span>
                                    </div>

                                    @if(\Carbon\Carbon::parse($reservation->start_date)->isFuture())
                                        <form action="{{ route('reservation.destroy', $reservation) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="bg-red-500 text-white px-2 rounded">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if($reservations->hasPages())
                            <div class="mt-4">
                                {{ $reservations->links() }}
                            </div>
                        @endif
                    @else
                        <p>No reservations found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>