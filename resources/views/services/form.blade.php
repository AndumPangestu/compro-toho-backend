@extends('layouts.app')

@section('name', 'Service Management')

@section('content')

<h1>
    @if (isset($viewMode) && $viewMode)
    View Service
    @elseif (isset($service))
    Edit Service
    @else
    Create Service
    @endif
</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        @if (isset($viewMode) && $viewMode)
        <h3>Service Details</h3>
        <p><strong>Name:</strong> {{ $service->name }}</p>
        <p><strong>Description:</strong> {{ $service->description }}</p>
        <img src="{{ $service->getFirstMediaUrl('services') }}" alt="Service Image" class="img-thumbnail" width="300">
        <br>
        <a href="{{ route('services.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        @else
        <form method="POST"
            action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($service))
            @method('PUT')
            @endif
            <div class="form-group">
                <label for="title">Name</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title..."
                    value="{{ old('title', $service->title ?? '') }}" required>
                @error('title')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                    id="description" rows="4" placeholder="Enter description..."
                    required>{{ isset($service) ? $service->description : old('description') }}</textarea>
                @error('description')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $description }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="image">Upload Service Image</label>
                <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                    onchange="previewImage(event)">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mt-3 position-relative" style="display: inline-block;">
                    <img id="image-preview" src="{{ isset($service) ? $service->getFirstMediaUrl('services') : '#' }}"
                        alt="Image Preview" class="img-thumbnail {{ isset($service) ? '' : 'd-none' }}" width="300">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                        style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        onclick="removeImage()">&times;</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ isset($service) ? 'Update Service' : 'Submit Service' }}
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
