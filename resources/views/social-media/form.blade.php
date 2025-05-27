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
                        <label for="icon">Icon</label>
                        <input type="file" class="form-control" name="icon" id="icon" placeholder="Enter icon..."
                            value="{{ old('icon', $socialMedia->icon ?? '') }}">
                        @error('icon')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($socialMedia) ? 'Update Social Media' : 'Submit Social Media' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
