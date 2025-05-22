@extends('layouts.app')

@section('title', 'Create Broadcast')


@section('content')
    <h1>{{ isset($broadcast) ? (isset($viewMode) ? 'View Broadcast' : 'Edit Broadcast') : 'Create Broadcast' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Broadcast Details</h3>
                <p><strong>Title:</strong> {{ $broadcast->title }}</p>
                <p><strong>Content:</strong> {{ $broadcast->content }}</p>
                <p><strong>Link:</strong> <a href="{{ $broadcast->link }}" target="_blank">{{ $broadcast->link }}</a></p>
                <p><strong>Created At:</strong> {{ $broadcast->created_at }}</p>
                <br>
                <a href="{{ route('broadcasts.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($broadcast) ? route('broadcasts.update', $broadcast->id) : route('broadcasts.store') }}">
                    @csrf
                    @if (isset($broadcast))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                            id="title" placeholder="Enter Broadcast Title..."
                            value="{{ isset($broadcast) ? $broadcast->title : old('title') }}" required>
                        @error('title')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="desc">content</label>
                        <textarea class="form-control @error('desc') is-invalid @enderror" name="content" id="desc" rows="4"
                            placeholder="Enter Broadcast content..." required>{{ isset($broadcast) ? $broadcast->desc : old('desc') }}</textarea>
                        @error('desc')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" name="link"
                            id="link" placeholder="Enter Broadcast Link..."
                            value="{{ isset($broadcast) ? $broadcast->link : old('link') }}">
                        @error('link')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($broadcast) ? 'Update Broadcast' : 'Submit Broadcast' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
