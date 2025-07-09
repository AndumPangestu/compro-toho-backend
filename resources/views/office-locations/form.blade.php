@extends('layouts.app')

@section('name', 'Office Location Management')

@section('content')

    <h1>
        @if (isset($viewMode) && $viewMode)
            View Office Location
        @elseif (isset($officeLocation))
            Edit Office Location
        @else
            Create Office Location
        @endif
    </h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Office Location Details</h3>
                <p><strong>Name:</strong> {{ $officeLocation->name }}</p>
                <p><strong>Address:</strong> {{ $officeLocation->address }}</p>
                <p><strong>Image:</strong></p>
                <img src="{{ $banner->getFirstMediaUrl('office_locations') }}" alt="Office Location Image"
                    class="img-thumbnail" width="300">
                <br>
                <a href="{{ route('office-locations.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($officeLocation) ? route('office-locations.update', $officeLocation->id) : route('office-locations.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($officeLocation))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter name..."
                            value="{{ old('name', $officeLocation->name ?? '') }}" required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="4"
                            placeholder="Enter address..." required>{{ isset($officeLocation) ? $officeLocation->address : old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $address }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" class="form-control" name="position" id="position"
                            placeholder="Enter position..." value="{{ old('position', $officeLocation->position ?? '') }}"
                            required>
                        @error('position')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="map_address">Map Address</label>
                        <textarea class="form-control @error('map_address') is-invalid @enderror" name="map_address" id="map_address"
                            rows="4" placeholder="Enter map address..." required>{{ isset($officeLocation) ? $officeLocation->map_address : old('map_address') }}</textarea>
                        @error('map_address')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $map_address }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image</label>
                        <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                            onchange="previewImage(event)">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="mt-3 position-relative" style="display: inline-block;">
                            <img id="image-preview"
                                src="{{ isset($officeLocation) ? $officeLocation->getFirstMediaUrl('office_locations') : '#' }}"
                                alt="Image Preview" class="img-thumbnail {{ isset($officeLocation) ? '' : 'd-none' }}"
                                width="300">
                            <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                                style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeImage()">&times;</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($officeLocation) ? 'Update Office Location' : 'Submit Office Location' }}
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
