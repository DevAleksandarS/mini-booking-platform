<x-mail::message>
    # Reservation Created

    Hello {{ $reservation->user->name }},

    You created a reservation for {{ $reservation->facility->name }}
    from {{ \Carbon\Carbon::parse($reservation->start_date)->toFormattedDateString() }}
    to {{ \Carbon\Carbon::parse($reservation->end_date)->toFormattedDateString() }}.

    Please confirm your reservation by clicking the button below.
    This link will expire in 1 hour.

    URL: {{ $url }}

    <x-mail::button :url="$url">
        Confirm Reservation
    </x-mail::button>

    Thanks, {{ config('app.name') }}
</x-mail::message>