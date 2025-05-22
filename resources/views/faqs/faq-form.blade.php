@extends('layouts.app')

@section('title', 'Create FAQ')

@section('content')
    <h1>{{ isset($faq) ? (isset($viewMode) ? 'View FAQ' : 'Edit FAQ') : 'Create FAQ' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>FAQ Details</h3>
                <p><strong>Question:</strong> {{ $faq->question }}</p>
                <p><strong>Answer:</strong> {{ $faq->answer }}</p>
                <br>
                <a href="{{ route('faqs.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST" action="{{ isset($faq) ? route('faqs.update', $faq->id) : route('faqs.store') }}">
                    @csrf
                    @if (isset($faq))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" class="form-control @error('question') is-invalid @enderror" name="question"
                            id="question" placeholder="Enter FAQ Question..."
                            value="{{ isset($faq) ? $faq->question : old('question') }}" required>
                        @error('question')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <textarea class="form-control @error('answer') is-invalid @enderror" name="answer" id="answer" rows="4"
                            placeholder="Enter FAQ Answer..." required>{{ isset($faq) ? $faq->answer : old('answer') }}</textarea>
                        @error('answer')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($faq) ? 'Update FAQ' : 'Submit FAQ' }}
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
            imgElement.src = reader.result;
            imgElement.classList.remove('d-none'); // Menampilkan gambar preview
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
