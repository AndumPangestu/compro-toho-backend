@extends('layouts.app')

@section('title', 'Create Testimonial')

@section('content')
    <h1>{{ isset($testimonial) ? (isset($viewMode) ? 'View Testimonial' : 'Edit Testimonial') : 'Create Testimonial' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Testimonial Details</h3>
                <p><strong>Message:</strong> {{ $testimonial->message }}</p>
                <p><strong>Sender Name:</strong> {{ $testimonial->sender_name }}</p>
                <p><strong>Organization:</strong> {{ $testimonial->organization ?? '-' }}</p>
                <p><strong>Sender Category:</strong> {{ ucfirst($testimonial->sender_category) }}</p>
                @if ($testimonial->getFirstMediaUrl('testimonials') !== null)
                    <img src="{{ $testimonial->getFirstMediaUrl('testimonials') }}" alt="Testimonial Image"
                        class="img-thumbnail" width="300">
                @endif
                <br>
                <a href="{{ route('testimonials.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($testimonial) ? route('testimonials.update', $testimonial->id) : route('testimonials.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($testimonial))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" rows="4"
                            placeholder="Enter testimonial message..." required>{{ isset($testimonial) ? $testimonial->message : old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sender_name">Sender Name</label>
                        <input type="text" class="form-control @error('sender_name') is-invalid @enderror"
                            name="sender_name" id="sender_name" placeholder="Enter sender name..."
                            value="{{ isset($testimonial) ? $testimonial->sender_name : old('sender_name') }}" required>
                        @error('sender_name')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="organization">Organization (Optional)</label>
                        <input type="text" class="form-control @error('organization') is-invalid @enderror"
                            name="organization" id="organization" placeholder="Enter organization name..."
                            value="{{ isset($testimonial) ? $testimonial->organization : old('organization') }}">
                        @error('organization')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sender_category">Sender Category</label>
                        <select class="form-control @error('sender_category') is-invalid @enderror" name="sender_category"
                            id="sender_category" required>
                            <option value="donor"
                                {{ isset($testimonial) && $testimonial->sender_category == 'donor' ? 'selected' : '' }}>
                                Donor</option>
                            <option value="partner"
                                {{ isset($testimonial) && $testimonial->sender_category == 'partner' ? 'selected' : '' }}>
                                Partner</option>
                            <option value="recipient"
                                {{ isset($testimonial) && $testimonial->sender_category == 'recipient' ? 'selected' : '' }}>
                                Recipient</option>
                        </select>
                        @error('sender_category')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Testimonial Image</label>
                        <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                            onchange="previewImage(event)">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="mt-3 position-relative" style="display: inline-block;">
                            <img id="image-preview"
                                src="{{ isset($testimonial) ? $testimonial->getFirstMediaUrl('testimonials') : '#' }}"
                                alt="Image Preview" class="img-thumbnail {{ isset($testimonial) ? '' : 'd-none' }}"
                                width="300">
                            <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                                style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeImage()">&times;</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($testimonial) ? 'Update Testimonial' : 'Submit Testimonial' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const imgElement = document.getElementById('image-preview');
            const removeButton = document.getElementById('remove-image');
            imgElement.src = reader.result;
            imgElement.classList.remove('d-none'); // Menampilkan gambar preview
            removeButton.classList.remove('d-none'); // Menampilkan tombol remove
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage() {
        const imgElement = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-image');
        const fileInput = document.getElementById('image');

        imgElement.src = '#';
        imgElement.classList.add('d-none'); // Sembunyikan gambar
        removeButton.classList.add('d-none'); // Sembunyikan tombol remove
        fileInput.value = ''; // Kosongkan input file
    }
</script>
