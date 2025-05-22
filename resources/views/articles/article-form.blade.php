@extends('layouts.app')

@push('styles')
<!-- jQuery (Versi penuh, bukan slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>

<!-- Summernote (Versi terbaru yang lebih stabil) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<!-- Bootstrap Tags Input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<style>
    .tags-container {
        background-color: #343a40;
        /* Warna gelap */
        padding: 10px;
        border-radius: 5px;
        color: white;
        /* Warna teks putih agar terlihat */
    }

    .tags-container label {
        display: block;
        /* Agar label berada di atas */
        font-weight: bold;
        margin-bottom: 5px;
    }

    .bootstrap-tagsinput {
        width: 100%;
        background: white;
        color: black;
        border: 1px solid #ced4da;
        padding: 5px;
        border-radius: 5px;
    }

    .bootstrap-tagsinput .tag {
        background: #007bff;
        /* Warna biru Bootstrap */
        color: white;
        padding: 5px;
        margin-right: 5px;
        border-radius: 3px;
    }
</style>
@endpush


@section('title', 'Article Management')

@section('content')
<h1>{{ isset($article) ? (isset($viewMode) ? 'View Article' : 'Edit Article') : 'Create Article' }}</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        @if (isset($viewMode) && $viewMode)
        <h3>Article Details</h3>
        <p><strong>Title:</strong> {{ $article->title }}</p>
        <p><strong>Slug:</strong> {{ $article->slug }}</p>
        <p><strong>Content:</strong> {!! $article->content !!}</p>
        <p><strong>Type:</strong> {{ $article->type }}</p>
        <p><strong>Description:</strong> {{ !!$article->description ?? '-' }}</p>
        <p><strong>Category:</strong> {{ $article->category->name ?? 'Uncategorized' }}</p>
        <p><strong>Donation:</strong> {{ $article->donation->title ?? '-' }}</p>
        <p><strong>Highlight:</strong> {{ $article->put_on_highlight ? 'Yes' : 'No' }}</p>
        <p><strong>Created At:</strong> {{ $article->created_at }}</p>
        <P><strong>Tags</strong>
            @if (isset($article->tags))
            @foreach ($article->tags as $tag) {{ $tag->name }} @endforeach
            @endif
        </P>
        <p><strong>Images:</strong></p>
        <img src="{{ $article->getFirstMediaUrl('articles') }}" alt="Article Image" class="img-thumbnail" width="300">
        <br>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        @else
        <form method="POST"
            action="{{ isset($article) ? route('articles.update', $article->id) : route('articles.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($article))
            @method('PUT')
            @endif
            <div class="form-group">
                <label for="title">Article Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Article Title..."
                    value="{{ isset($article) ? $article->title : old('title') }}" required>
                @error('title')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"
                    placeholder="Enter Article Description..."
                    rows="3">{{ old('description', $article->description ?? '') }}</textarea>
                @error('description')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" name="content"
                    id="summernote">{{ isset($article) ? $article->content : old('content') }}</textarea>
                @error('content')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" name="type" id="type" required>
                    <option value="news" {{ (isset($article) && $article->type == 'news') || old('type') == 'news' ?
                        'selected' : '' }}>
                        Berita
                    </option>
                    <option value="kindness_story" {{ (isset($article) && $article->type == 'kindness_story') ||
                        old('type') == 'kindness_story' ? 'selected' : '' }}>
                        Cerita Kebaikan
                    </option>
                    <option value="release" {{ (isset($article) && $article->type == 'release') || old('type') ==
                        'release' ? 'selected' : '' }}>
                        Rilis
                    </option>
                    <option value="infographics" {{ (isset($article) && $article->type == 'infographics') || old('type')
                        == 'infographics' ? 'selected' : '' }}>
                        Infografis
                    </option>
                </select>

                @error('type')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" name="category_id" id="category_id">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ isset($article) && $article->category_id == $category->id ?
                        'selected' : (old('category_id') == $category->id ? 'selected' : '') }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="donation_id">Donation</label>
                <select class="form-control" name="donation_id" id="donation_id">
                    <option value="">Select Donation</option>
                    @foreach ($donations as $donation)
                    <option value="{{ $donation->id }}" {{ isset($selectedDonation) && $selectedDonation->id ==
                        $donation->id ?
                        'selected' : (old('donation_id') == $donation->id ? 'selected' : '') }}>
                        {{ $donation->title }}</option>
                    @endforeach
                </select>
                @error('donation_id')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" class="form-control" name="tags" id="tags"
                    value="{{ isset($article) ? implode(',', $article->tags->pluck('name')->toArray()) : old('tags') }}"
                    data-role="tagsinput" placeholder="Enter tags">
                @error('tags')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="put_on_highlight">Highlight</label>
                <select class="form-control" name="put_on_highlight" id="put_on_highlight">
                    <option value="0" {{ isset($article) && !$article->put_on_highlight ? 'selected' : '' }}>
                        No</option>
                    <option value="1" {{ isset($article) && $article->put_on_highlight ? 'selected' : '' }}>
                        Yes</option>
                </select>
                @error('put_on_highlight')
                <span class="text-danger"><strong>{{ $message }}</strong> </span>
                @enderror
            </div>
            @if (!isset($article))
            <div class="form-group">
                <label>
                    <input type="checkbox" name="share_via_email" value="1">
                    Share this article via email
                </label>
            </div>
            @endif
            <div class="form-group">
                <label for="image">Upload Article Image</label>
                <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                    onchange="previewImage(event)">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mt-3 position-relative" style="display: inline-block;">
                    <img id="image-preview" src="{{ isset($article) ? $article->getFirstMediaUrl('articles') : '#' }}"
                        alt="Image Preview" class="img-thumbnail {{ isset($article) ? '' : 'd-none' }}" width="300">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                        style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        onclick="removeImage()">&times;</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ isset($article) ? 'Update Article' : 'Submit Article' }}
            </button>
        </form>
        @endif
    </div>
</div>

<script>
    $('#summernote').summernote({
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
        });
</script>
<script>
    $(document).ready(function () {
        // Inisialisasi tags input
        $('input[data-role="tagsinput"]').tagsinput({
            confirmKeys: [13, 44], // Enter dan tanda koma
            trimValue: true
        });
    });


    $('#donation_id').select2({
            placeholder: "Select Donation",
            allowClear: true
    });

    $('#category_id').select2({
        placeholder: "Select Category",
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
