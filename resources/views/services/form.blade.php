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
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                            rows="4" placeholder="Enter description..." required>{{ isset($service) ? $service->description : old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $description }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image" id="image"
                            placeholder="Enter Image..." value="{{ old('image', $service->image ?? '') }}">
                        @error('image')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($service) ? 'Update Service' : 'Submit Service' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
