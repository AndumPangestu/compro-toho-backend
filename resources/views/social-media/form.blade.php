@extends('layouts.app')

@section('name', 'Social Media Management')

@section('content')

<h1>
    @if (isset($viewMode) && $viewMode)
    View Social Media
    @elseif (isset($socialMedia))
    Edit Social Media
    @else
    Create Social Media
    @endif
</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        @if (isset($viewMode) && $viewMode)
        <h3>Social Media Details</h3>
        <p><strong>Name:</strong> {{ $socialMedia->name }}</p>
        <p><strong>URL:</strong> {{ $socialMedia->url }}</p>
        <p><strong>Image:</strong></p>
        <img src="{{ $banner->getFirstMediaUrl('social_media') }}" alt="Social Media Image" class="img-thumbnail"
            width="300">
        <br>
        <a href="{{ route('social-media.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        @else
        <form method="POST"
            action="{{ isset($socialMedia) ? route('social-media.update', $socialMedia->id) : route('social-media.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($socialMedia))
            @method('PUT')
            @endif
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter name..."
                    value="{{ old('name', $socialMedia->name ?? '') }}" required>
                @error('name')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" class="form-control" name="url" id="url" placeholder="Enter url..."
                    value="{{ old('url', $socialMedia->url ?? '') }}" required>
                @error('url')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
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
                        src="{{ isset($socialMedia) ? $socialMedia->getFirstMediaUrl('social_media') : '#' }}"
                        alt="Image Preview" class="img-thumbnail {{ isset($socialMedia) ? '' : 'd-none' }}" width="300">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                        style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        onclick="removeImage()">&times;</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ isset($socialMedia) ? 'Update Social Media' : 'Submit Social Media' }}
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
