<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <img src="{{ Storage::url($facility->image) }}" alt="{{ $facility->name }}" class="w-36">
                    <!-- TODO: Update image-->

                    <p>Name: {{ $facility->name }}</p>
                    <p>Location: {{ $facility->location->city }}, {{ $facility->location->country }}</p>
                    <p>Price: ${{ $facility->price }}</p>
                    <p>Number of beds: {{ $facility->number_of_beds }}</p>
                    <p>Max people: {{ $facility->max_people }}</p>
                    <p>Description: {{ $facility->description }}</p>

                    <div class="mt-4">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('facility.destroy', $facility) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 rounded"
                                        onclick="return confirm('Are you sure you want to delete this facility?')">
                                        Delete
                                    </button>
                                </form>

                                <button type="button" class="bg-blue-700 text-white px-2 rounded" data-bs-toggle="modal"
                                    data-bs-target="#facilityPopup">
                                    Update
                                </button>
                            @else
                                <form action="{{ route('reservation.store') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">

                                    <div class="mb-4">
                                        <label for="start_date" class="block mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                            class="w-full border rounded px-3 py-2" required>
                                        @error('start_date')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="end_date" class="block mb-1">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="w-full border rounded px-3 py-2"
                                            value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit" class="bg-blue-700 text-white px-2 rounded">
                                        Reserve
                                    </button>
                                </form>

                                @if(session('success'))
                                    <div class="mt-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="mt-4">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if($my_review)
                                    <div class="mt-8">
                                        <h3 class="font-medium">My review:</h3>

                                        <div class="mt-2">
                                            <p>Rated {{ $my_review->rating }}/5</p>
                                            <p>{{ $my_review->comment }}</p>

                                            <form action="{{ route('review.destroy', $my_review) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="bg-red-500 text-white px-2 rounded"
                                                    onclick="return confirm('Are you sure you want to delete your review?')">
                                                    Delete
                                                </button>
                                            </form>

                                            <button type="button" class="bg-blue-700 text-white px-2 rounded" data-bs-toggle="modal"
                                                data-bs-target="#reviewPopup">
                                                Update
                                            </button>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="reviewPopup" tabindex="-1" aria-labelledby="reviewPopupLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="reviewPopupLabel">Update
                                                        Review</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="mt-4" action="{{ route('review.update', $my_review) }}"
                                                        method="POST" id="updateReviewForm{{ $my_review->id }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-4">
                                                            <label for="rating">Rating:</label>
                                                            <select class="w-full border rounded px-3 py-" name="rating" id="rating"
                                                                required>
                                                                <option value="">Select rating</option>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <option value="{{ $i }}" {{ $my_review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="comment">Comment:</label>
                                                            <textarea class="w-full border rounded px-3 py-" name="comment"
                                                                id="comment" required>{{ $my_review->comment }}</textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        form="updateReviewForm{{ $my_review->id }}">Save
                                                        changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <form class="mt-4" action="{{ route('review.store', $facility) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rating">Rating:</label>
                                            <select class="w-full border rounded px-3 py-" name="rating" id="rating" required>
                                                <option value="">Select rating</option>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label for="comment">Comment:</label>
                                            <textarea class="w-full border rounded px-3 py-" name="comment" id="comment"
                                                required></textarea>
                                        </div>

                                        <button class="bg-blue-700 text-white px-2 rounded" type="submit">Submit Review</button>
                                    </form>
                                @endif
                            @endif
                        @endauth

                        @guest
                            <a href="{{ route('login') }}">Login to reserve</a>
                        @endguest

                        <div class="mt-8">
                            <h3 class="font-medium">Reviews:</h3>

                            @if($reviews->count())
                                @foreach($reviews as $review)
                                    <div class="mt-2">
                                        <p>{{ $review->user->name }} rated {{ $review->rating }}/5</p>
                                        <p>{{ $review->comment }}</p>
                                        @auth
                                            @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('review.destroy', $review) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="bg-red-500 text-white px-2 rounded"
                                                        onclick="return confirm('Are you sure you want to delete this review?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                @endforeach

                                <div class="mt-4">
                                    {{ $reviews->links() }}
                                </div>
                            @else
                                <p>No reviews found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="facilityPopup" tabindex="-1" aria-labelledby="facilityPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="facilityPopupLabel">Update
                        Facility</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('facility.update', $facility) }}" method="POST"
                        id="updateFacilityForm{{ $facility->id }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name{{ $facility->id }}" class="form-label">Name</label>
                            <input type="text" id="name{{ $facility->id }}" name="name"
                                value="{{ old('name', $facility->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description{{ $facility->id }}" class="form-label">Description</label>
                            <textarea id="description{{ $facility->id }}" name="description" required
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $facility->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location_id{{ $facility->id }}" class="form-label">Location</label>
                            <select id="location_id{{ $facility->id }}" name="location_id" required
                                class="form-select @error('location_id') is-invalid @enderror">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id', $facility->location_id) == $location->id ? 'selected' : '' }}>
                                        {{ $location->city }}, {{ $location->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price{{ $facility->id }}" class="form-label">Price per night</label>
                            <input type="number" id="price{{ $facility->id }}" name="price" step="0.01" min="1"
                                value="{{ old('price', $facility->price) }}"
                                class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="number_of_beds{{ $facility->id }}" class="form-label">Number of Beds</label>
                            <input type="number" id="number_of_beds{{ $facility->id }}" name="number_of_beds" min="1"
                                value="{{ old('number_of_beds', $facility->number_of_beds) }}"
                                class="form-control @error('number_of_beds') is-invalid @enderror" required>
                            @error('number_of_beds')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="max_people{{ $facility->id }}" class="form-label">Max People</label>
                            <input type="number" id="max_people{{ $facility->id }}" name="max_people" min="1"
                                value="{{ old('max_people', $facility->max_people) }}"
                                class="form-control @error('max_people') is-invalid @enderror" required>
                            @error('max_people')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateFacilityForm{{ $facility->id }}">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>