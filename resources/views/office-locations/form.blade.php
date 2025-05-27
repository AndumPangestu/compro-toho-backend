@extends('layouts.app')

@section('name', 'Office Location Management')

@section('content')

    <h1>
        @if (isset($viewMode) && $viewMode)
            View Office Location
        @elseif (isset($officeLocation))
            Edit Office Location
        @else
            Create Office Location
        @endif
    </h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Office Location Details</h3>
                <p><strong>Name:</strong> {{ $officeLocation->name }}</p>
                <p><strong>Address:</strong> {{ $officeLocation->address }}</p>
                <a href="{{ route('office-locations.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($officeLocation) ? route('office-locations.update', $officeLocation->id) : route('office-locations.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($officeLocation))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter name..."
                            value="{{ old('name', $officeLocation->name ?? '') }}" required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="4"
                            placeholder="Enter address..." required>{{ isset($officeLocation) ? $officeLocation->address : old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $address }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <input type="file" class="form-control" name="icon" id="icon" placeholder="Enter icon..."
                            value="{{ old('icon', $officeLocation->icon ?? '') }}">
                        @error('icon')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($officeLocation) ? 'Update Office Location' : 'Submit Office Location' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
