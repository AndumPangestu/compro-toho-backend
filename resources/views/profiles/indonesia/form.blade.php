@extends('layouts.app')

@section('title', 'Indonesia Profile Management')

@push('styles')
<!-- jQuery (Versi penuh, bukan slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush

@section('content')

<h1>Profile</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('profiles.indonesia.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Banner Title..."
                    value="{{ isset($profileData) ? $profileData->name : old('name') }}" required>
                @error('name')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" placeholder="Enter Description..."
                    rows="3">{{ isset($profileData) ? $profileData->description : old('description') }}</textarea>
                @error('description')
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
                    <input type="hidden" name="image_url" id="image_url"
                        value="{{ isset($profile) ? $profile->getFirstMediaUrl('indonesia_profile') : '' }}">
                    <img id="image-preview"
                        src="{{ isset($profile) ? $profile->getFirstMediaUrl('indonesia_profile') : '#' }}"
                        alt="Image Preview" class="img-thumbnail {{ isset($profile) ? '' : 'd-none' }}" width="300">
                    <button type="button" id="remove-image"
                        class="btn btn-danger btn-sm {{ isset($profile) ? '' : 'd-none' }} position-absolute"
                        style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        onclick="removeImage()">&times;</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Update Profile
            </button>
        </form>
    </div>
</div>
<script>
    $('#article_id').select2({
            placeholder: "Select Article",
            allowClear: true
        });
</script>

@endsection
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const imgElement = document.getElementById('image-preview');
            const removeButton = document.getElementById('remove-image');
            const imageUrlInput = document.getElementById('image_url');
            imgElement.src = reader.result;
            imageUrlInput.value = reader.result;
            imgElement.classList.remove('d-none'); // Menampilkan gambar preview
            removeButton.classList.remove('d-none'); // Menampilkan tombol remove
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage() {
        const imgElement = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-image');
        const fileInput = document.getElementById('image');
        const imageUrlInput = document.getElementById('image_url');
        imageUrlInput.value = '';

        imgElement.src = '#';
        imgElement.classList.add('d-none'); // Sembunyikan gambar
        removeButton.classList.add('d-none'); // Sembunyikan tombol remove
        fileInput.value = ''; // Kosongkan input file
    }
</script>
