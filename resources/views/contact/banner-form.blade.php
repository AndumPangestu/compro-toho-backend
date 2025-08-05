@extends('layouts.app')

@section('title', 'Banner Management')

@push('styles')
    <!-- jQuery (Versi penuh, bukan slim) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
@endpush

@section('content')

    <h1>{{ isset($banner) ? (isset($viewMode) ? 'View Banner' : 'Edit Banner') : 'Create Banner' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Banner Details</h3>
                <p><strong>Title:</strong> {{ $banner->title }}</p>
                <p><strong>Description:</strong> {{ $banner->description }}</p>
                <p><strong>Link:</strong> {{ $banner->link }}</p>
                <p><strong>Related Article:</strong> {{ $banner->article->title ?? 'None' }}</p>
                <p><strong>Image:</strong></p>
                <img src="{{ $banner->getFirstMediaUrl('banners') }}" alt="Banner Image" class="img-thumbnail" width="300">
                <br>
                <a href="{{ route('banners.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($banner) ? route('banners.update', $banner->id) : route('banners.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($banner))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="title">Banner Title</label>
                        <input type="text" class="form-control" name="title" id="title"
                            placeholder="Enter Banner Title..." value="{{ isset($banner) ? $banner->title : old('title') }}"
                            required>
                        @error('title')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Banner Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Banner Description..."
                            rows="3">{{ isset($banner) ? $banner->description : old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="link">Banner Link</label>
                        <input type="url" class="form-control" name="link" id="link"
                            placeholder="Enter Banner Link..." value="{{ isset($banner) ? $banner->link : old('link') }}"
                            required>
                        @error('link')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="article_id">Related Article (Optional)</label>
                        <select class="form-control" name="article_id" id="article_id">
                            <option value="">Select an Article</option>
                            @foreach ($articles as $article)
                                <option value="{{ $article->id }}"
                                    {{ isset($banner) && $banner->article_id == $article->id
                                        ? 'selected'
                                        : (old('article_id') == $article->id
                                            ? 'selected'
                                            : '') }}>
                                    {{ $article->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('article_id')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Banner Image</label>
                        <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                            onchange="previewImage(event)">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="mt-3 position-relative" style="display: inline-block;">
                            <img id="image-preview" src="{{ isset($banner) ? $banner->getFirstMediaUrl('banners') : '#' }}"
                                alt="Image Preview" class="img-thumbnail {{ isset($banner) ? '' : 'd-none' }}"
                                width="300">
                            <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                                style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                onclick="removeImage()">&times;</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($banner) ? 'Update Banner' : 'Submit Banner' }}
                    </button>
                </form>
            @endif
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
