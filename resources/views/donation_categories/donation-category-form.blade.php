@extends('layouts.app')

@section('name', 'Donation Category Management')

@section('content')

    <h1>
        @if (isset($viewMode) && $viewMode)
            View Donation Category
        @elseif (isset($category))
            Edit Donation Category
        @else
            Create Donation Category
        @endif
    </h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Donation Category Details</h3>
                <p><strong>Name:</strong> {{ $category->name }}</p>
                <p><strong>Description:</strong> {{ $category->description }}</p>
                <a href="{{ route('donation-categories.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($category) ? route('donation-categories.update', $category->id) : route('donation-categories.store') }}">
                    @csrf
                    @if (isset($category))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter Category name..." value="{{ old('name', $category->name ?? '') }}" required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Category Description</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Enter Category Description..."
                            rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($category) ? 'Update Category' : 'Submit Category' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
