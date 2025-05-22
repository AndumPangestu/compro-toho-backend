@extends('layouts.app')

@section('title', 'Partner Management')

@section('content')

    <h1>{{ isset($partner) ? (isset($viewMode) ? 'View Partner' : 'Edit Partner') : 'Create Partner' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Partner Details</h3>
                <p><strong>Title:</strong> {{ $partner->title }}</p>
                <p><strong>Description:</strong> {{ $partner->description }}</p>
                <p><strong>Related Article:</strong> {{ $partner->article->title ?? 'None' }}</p>
                <p><strong>Image:</strong></p>
                <img src="{{ $partner->getFirstMediaUrl('partners') }}" alt="partner Image" class="img-thumbnail"
                    width="300">
                <br>
                <a href="{{ route('partners.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($partner) ? route('partners.update', $partner->id) : route('partners.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($partner))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Partner Title</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter partner name..." value="{{ isset($partner) ? $partner->name : old('name') }}"
                            required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">partner Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter partner Description..."
                            rows="3">{{ isset($partner) ? $partner->description : old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Partner Image</label>
                        <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                            onchange="previewImage(event)">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="mt-3 position-relative" style="display: inline-block;">
                            <img id="image-preview"
                                src="{{ isset($partner) ? $partner->getFirstMediaUrl('partners') : '#' }}"
                                alt="Image Preview" class="img-thumbnail {{ isset($partner) ? '' : 'd-none' }}"
                                width="300">
                            <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                                style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeImage()">&times;</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($partner) ? 'Update partner' : 'Submit partner' }}
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
